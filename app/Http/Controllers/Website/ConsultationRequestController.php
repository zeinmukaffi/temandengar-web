<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\ConsultationRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Str;

class ConsultationRequestController extends Controller
{
    public function create(): View
    {
        return view('public.consultation-form');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'alias' => ['required', 'string', 'max:100'],

            'email' => [
                'nullable',
                'required_without:phone',
                'email',
                'max:150',
            ],

            'phone' => [
                'nullable',
                'required_without:email',
                'string',
                'max:20',
            ],

            'topic' => [
                'required',
                'in:academic,personal,social,organization,other',
            ],

            'description' => [
                'required',
                'string',
                'min:10',
                'max:3000',
            ],

            'preferred_method' => [
                'required',
                'in:online,offline',
            ],

            'preferred_date' => [
                'nullable',
                'date',
                'after_or_equal:today',
            ],

            'preferred_time' => [
                'nullable',
                'string',
                'max:50',
            ],

            'consent' => ['accepted'],
        ], [
            'alias.required' => 'Nama panggilan wajib diisi.',
            'email.required_without' => 'Isi email atau nomor WhatsApp.',
            'phone.required_without' => 'Isi nomor WhatsApp atau email.',
            'topic.required' => 'Pilih topik konsultasi.',
            'description.required' => 'Ceritakan kebutuhan konsultasimu.',
            'consent.accepted' => 'Persetujuan penggunaan data wajib diberikan.',
            'preferred_date.after_or_equal' => 'Tanggal tidak boleh kurang dari hari ini.',
        ]);

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