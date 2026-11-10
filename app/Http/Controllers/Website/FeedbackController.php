<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\FeedbackReminderDelivery;
use App\Services\N8nWebhookService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;

class FeedbackController extends Controller
{
    public function create(
        Request $request,
        Consultation $consultation
    ): View {

        // Hanya konsultasi selesai yang boleh menerima feedback
        abort_unless(
            $consultation->session_status === 'completed',
            404
        );

        // Satu konsultasi hanya boleh memiliki satu feedback
        if ($consultation->feedback()->exists()) {
            return view('public.feedback.closed');
        }

        // Pertahankan masa berlaku link dari URL awal
        $expiresAt = Carbon::createFromTimestamp(
            (int) $request->query('expires')
        );

        // Membuat URL khusus untuk pengiriman feedback
        $storeUrl = URL::temporarySignedRoute(
            'public.feedback.store',
            $expiresAt,
            ['consultation' => $consultation->id]
        );

        return view('public.feedback.create', compact(
            'consultation',
            'storeUrl'
        ));
    }

    public function store(
        Request $request,
        Consultation $consultation
    ): RedirectResponse {

        abort_unless(
            $consultation->session_status === 'completed',
            404
        );

        // Cegah pengiriman feedback lebih dari satu kali
        abort_if(
            $consultation->feedback()->exists(),
            404
        );

        // Validasi input
        $validated = $request->validate([
            'display_name' => [
                'nullable',
                'string',
                'max:100',
            ],

            'rating' => [
                'required',
                'integer',
                'min:1',
                'max:5',
            ],

            'comment' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'consent_to_publish' => [
                'sometimes',
                'boolean',
            ],
        ], [
            'rating.required' => 'Silakan berikan rating.',
            'rating.min' => 'Rating minimal 1 bintang.',
            'rating.max' => 'Rating maksimal 5 bintang.',
            'consent_to_publish.boolean' => 'Persetujuan tidak valid.',
        ]);

        // Ambil nilai persetujuan
        $consent = $request->boolean('consent_to_publish');

        $comment = trim($validated['comment'] ?? '');

        // Jika menyetujui publikasi, komentar wajib tersedia
        if ($consent && $comment === '') {

            return back()
                ->withErrors([
                    'comment' => 'Komentar wajib diisi jika ingin memberikan izin publikasi.',
                ])
                ->withInput();

        }

        // ========================================
        // STORE CONSULTATION FEEDBACK
        // ========================================

        $feedback = $consultation->feedback()->create([

            'display_name' => $validated['display_name'] ?? 'Mahasiswa',

            'rating' => $validated['rating'],

            'comment' => $comment !== '' ? $comment : null,

            'consent_to_publish' => $consent,

            // Semua feedback harus melewati moderasi konselor
            'is_published' => false,

            'published_at' => null,

        ]);

        // ========================================
        // CANCEL PENDING FEEDBACK REMINDER
        // ========================================

                // Mahasiswa sudah mengirim feedback.
        // Reminder yang belum terkirim tidak diperlukan lagi.

        FeedbackReminderDelivery::query()
            ->where('consultation_id', $consultation->id)
            ->whereIn('status', [
                'awaiting_invitation',
                'pending',
            ])
            ->update([
                'status' => 'skipped',
            ]);


        // ========================================
// WF-04: FEEDBACK SUBMITTED EVENT
// ========================================

        // Ambil data mahasiswa dan konselor
        $consultation->loadMissing([
            'consultationRequest',
            'counselor',
        ]);

        $consultationRequest = $consultation->consultationRequest;

        $counselor = $consultation->counselor;


        // URL webhook WF-04
        $feedbackWebhookUrl = config(
            'services.n8n.feedback_webhook_url'
        );


        // Pastikan data dan penerima tersedia
        if (
            $consultationRequest &&
            $counselor &&
            filled($counselor->email) &&
            filled($feedbackWebhookUrl)
        ) {

            $payload = [

                'feedback_id' => $feedback->id,

                'consultation_id' => $consultation->id,

                'ticket_number' => $consultationRequest->ticket_number,

                'student' => [

                    'display_name' => $consultationRequest->alias,

                ],

                'counselor' => [

                    'name' => $counselor->name,

                    'email' => $counselor->email,

                ],

                'rating' => $feedback->rating,

                'submitted_at' => $feedback->created_at
                    ? $feedback->created_at->toIso8601String()
                    : now()->toIso8601String(),

            ];


            // Kirim event ke n8n
            app(N8nWebhookService::class)->send(

                'consultation.feedback_submitted',

                $payload,

                $feedbackWebhookUrl

            );

        } else {

            Log::warning(
                'Feedback submitted event was not dispatched.',
                [
                    'feedback_id' => $feedback->id,
                    'consultation_id' => $consultation->id,
                ]
            );

        }

        app(\App\Services\CounselorNotificationService::class)
            ->notifyCounselors(
                'feedback_received',

                'Feedback baru diterima',

                'Feedback baru telah diterima untuk konsultasi dengan nomor tiket '
                . $consultation->consultationRequest->ticket_number
                . '.',

                route(
                    'admin.feedback.index',
                    [],
                    false
                ),

                'bi-star'
            );

        return redirect()
            ->route('home')
            ->with(
                'success',
                'Terima kasih! Feedback kamu berhasil dikirim.'
            );
    }
}