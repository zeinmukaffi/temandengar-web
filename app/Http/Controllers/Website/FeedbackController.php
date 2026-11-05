<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\ConsultationFeedback;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

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

        // Simpan feedback, baik private maupun publik
        $consultation->feedback()->create([

            'display_name' => $validated['display_name'] ?? 'Mahasiswa',

            'rating' => $validated['rating'],

            'comment' => $comment !== '' ? $comment : null,

            'consent_to_publish' => $consent,

            // Semua feedback harus melewati moderasi konselor
            'is_published' => false,

            'published_at' => null,

        ]);

        return redirect()
            ->route('home')
            ->with(
                'success',
                'Terima kasih! Feedback kamu berhasil dikirim.'
            );
    }
}