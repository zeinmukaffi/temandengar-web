<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreConsultationRequest;
use App\Models\ConsultationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ConsultationRequestController extends Controller
{
    public function create(): View
    {
        return view('public.consultation-form');
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
            'status' => 'pending',
            'consent_at' => now(),
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