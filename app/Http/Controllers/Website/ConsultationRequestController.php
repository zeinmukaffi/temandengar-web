<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreConsultationRequest;
use App\Models\ConsultationRequest;
use App\Models\User;
use App\Services\N8nWebhookService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ConsultationRequestController extends Controller
{
    public function create(): View
    {
        $counselors = User::query()
            ->where('is_public_profile', true)
            ->where('is_accepting_consultations', true)
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'headline',
                'specialties',
                'photo_path',
            ]);

        return view('public.consultation-form', compact('counselors'));
    }

    public function store(
        StoreConsultationRequest $request
    ): RedirectResponse {

        $validated = $request->validated();

        $consultationRequest = ConsultationRequest::create([
            'ticket_number' => $this->generateTicketNumber(),
            'alias' => $validated['alias'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'topic' => $validated['topic'],
            'description' => $validated['description'],
            'preferred_method' => $validated['preferred_method'],
            'preferred_date' => $validated['preferred_date'] ?? null,
            'preferred_time' => $validated['preferred_time'] ?? null,
            'ai_processing_consent' => $request->boolean(
                'ai_processing_consent'
            ),
            'ai_processing_consented_at' => $request->boolean(
                'ai_processing_consent'
            ) ? now() : null,
            'status' => 'pending',
            'consent_at' => now(),
            'preferred_counselor_id' =>
                $validated['preferred_counselor_id'] ?? null,
        ]);

        app(\App\Services\CounselorNotificationService::class)
            ->notifyCounselors(
                'new_consultation_request',

                'Pengajuan konsultasi baru',

                'Ada pengajuan baru dengan nomor tiket '
                . $consultationRequest->ticket_number . '.',

                route(
                    'admin.consultations.show',
                    $consultationRequest,
                    false
                ),

                'bi-inbox'
            );

        // ========================================
// WF-01: NEW CONSULTATION NOTIFICATION
// ========================================

        // Ambil relasi konselor preferensi mahasiswa
        $consultationRequest->load('preferredCounselor');

        $preferredCounselor = $consultationRequest->preferredCounselor;

        // Tentukan penerima email konselor
        if ($preferredCounselor) {

            $counselorEmail = $preferredCounselor->email;

            $counselorName = $preferredCounselor->name;

            $notificationType = 'preferred_counselor';

        } else {

            // Jika mahasiswa tidak memilih konselor,
            // kirim pemberitahuan ke koordinator.
            $counselorEmail = config('services.n8n.counselor_inbox');

            $counselorName = 'Koordinator Konselor';

            $notificationType = 'coordinator_queue';

        }


        // Pastikan email konselor tersedia
        if (blank($counselorEmail)) {

            Log::error(
                'WF-01 not dispatched: counselor recipient email is missing.',
                [
                    'request_id' => $consultationRequest->id,
                ]
            );

        } else {

            $topicLabels = [
                'academic' => 'Akademik',
                'personal' => 'Pengembangan Diri',
                'social' => 'Pertemanan',
                'organization' => 'Organisasi',
                'other' => 'Lainnya',
            ];

            $methodLabels = [
                'online' => 'Online',
                'offline' => 'Tatap Muka',
            ];


            // Kirim event ke n8n
            app(N8nWebhookService::class)->send(
                'consultation.requested',
                [

                    'ticket_number' => $consultationRequest->ticket_number,

                    'topic' => $consultationRequest->topic,

                    'topic_label' => $topicLabels[
                        $consultationRequest->topic
                    ] ?? 'Lainnya',

                    'preferred_method' => $consultationRequest->preferred_method,

                    'preferred_method_label' => $methodLabels[
                        $consultationRequest->preferred_method
                    ] ?? 'Belum ditentukan',

                    'preferred_counselor_name' =>
                        $preferredCounselor?->name,

                    'student' => [

                        'display_name' => $consultationRequest->alias,

                        'email' => $consultationRequest->email,

                    ],

                    'counselor' => [

                        'name' => $counselorName,

                        'email' => $counselorEmail,

                        'notification_type' => $notificationType,

                    ],

                ]
            );

        }

        return redirect()
            ->route('consultations.success')
            ->with('ticket_number', $consultationRequest->ticket_number);
    }

    private function generateTicketNumber(): string
    {
        do {
            $ticket = 'TD-' . now()->format('Y') . '-'
                . strtoupper(Str::random(6));
        } while (
            ConsultationRequest::where('ticket_number', $ticket)->exists()
        );

        return $ticket;
    }

    public function success(): View|RedirectResponse
    {
        if (!session()->has('ticket_number')) {
            return redirect()->route('consultations.create');
        }

        return view('public.success');
    }
}