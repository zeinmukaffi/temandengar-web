<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\ConsultationRequest;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ScheduleController extends Controller
{
    public function index(Request $request): View
    {
        $schedules = Consultation::with([
            'consultationRequest',
            'counselor'
        ])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;

                $query->whereHas('consultationRequest', function ($q) use ($search) {
                    $q->where('ticket_number', 'like', "%{$search}%")
                        ->orWhere('alias', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('session_status', $request->status);
            })
            ->orderByDesc('scheduled_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.schedules.index', compact('schedules'));
    }

    public function create(ConsultationRequest $consultationRequest): View
    {
        if (!in_array($consultationRequest->status, ['pending', 'reviewing'])) {
            abort(403, 'Permintaan ini tidak dapat dijadwalkan.');
        }

        if ($consultationRequest->consultation()->exists()) {
            return redirect()
                ->route('admin.consultations.show', $consultationRequest)
                ->withErrors([
                    'schedule' => 'Permintaan ini sudah memiliki jadwal.'
                ]);
        }

        return view(
            'admin.schedules.create',
            compact('consultationRequest')
        );
    }

    public function store(
        Request $request,
        ConsultationRequest $consultationRequest
    ): RedirectResponse {

        if (!in_array($consultationRequest->status, ['pending', 'reviewing'])) {
            abort(403, 'Permintaan ini tidak dapat dijadwalkan.');
        }

        if ($consultationRequest->consultation()->exists()) {
            return back()->withErrors([
                'schedule' => 'Permintaan ini sudah memiliki jadwal.'
            ]);
        }

        $validated = $request->validate([
            'scheduled_at' => ['required', 'date', 'after:now'],
            'method' => ['required', Rule::in(['online', 'offline'])],
            'meeting_link' => ['nullable', 'url', 'max:255'],
            'location' => [
                'nullable',
                'required_if:method,offline',
                'string',
                'max:255'
            ],
        ]);

        DB::transaction(function () use ($validated, $consultationRequest) {

            $consultationRequest->consultation()->create([
                'counselor_id' => auth()->id(),
                'scheduled_at' => $validated['scheduled_at'],
                'method' => $validated['method'],
                'meeting_link' => $validated['meeting_link'] ?? null,
                'location' => $validated['location'] ?? null,
                'session_status' => 'scheduled',
            ]);

            $oldStatus = $consultationRequest->status;

            $consultationRequest->update([
                'status' => 'scheduled',
            ]);

            ActivityLog::create([
                'user_id' => auth()->id(),
                'request_id' => $consultationRequest->id,
                'action' => 'schedule_created',
                'description' => "Jadwal konsultasi dibuat. Status permintaan berubah dari {$oldStatus} menjadi scheduled.",
            ]);
        });

        return redirect()
            ->route('admin.consultations.show', $consultationRequest)
            ->with('success', 'Jadwal konsultasi berhasil dibuat.');
    }

    public function edit(Consultation $consultation): View
    {
        if ($consultation->session_status !== 'scheduled') {
            abort(403, 'Jadwal ini tidak dapat diedit.');
        }

        return view('admin.schedules.edit', compact('consultation'));
    }

    public function update(
        Request $request,
        Consultation $consultation
    ): RedirectResponse {

        if ($consultation->session_status !== 'scheduled') {
            abort(403, 'Jadwal ini tidak dapat diedit.');
        }

        $validated = $request->validate([
            'scheduled_at' => ['required', 'date', 'after:now'],
            'method' => ['required', Rule::in(['online', 'offline'])],
            'meeting_link' => ['nullable', 'url', 'max:255'],
            'location' => [
                'nullable',
                'required_if:method,offline',
                'string',
                'max:255'
            ],
        ]);

        DB::transaction(function () use ($validated, $consultation) {

            $consultation->update($validated);

            ActivityLog::create([
                'user_id' => auth()->id(),
                'request_id' => $consultation->request_id,
                'action' => 'schedule_updated',
                'description' => 'Jadwal konsultasi diperbarui.',
            ]);
        });

        return redirect()
            ->route('admin.schedules.index')
            ->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function complete(Consultation $consultation): RedirectResponse
    {
        if ($consultation->session_status !== 'scheduled') {
            return back()->withErrors([
                'schedule' => 'Jadwal ini tidak dapat diselesaikan.'
            ]);
        }

        DB::transaction(function () use ($consultation) {

            $consultation->update([
                'session_status' => 'completed',
                'completed_at' => now(),
            ]);

            $consultation->consultationRequest()->update([
                'status' => 'completed',
            ]);

            ActivityLog::create([
                'user_id' => auth()->id(),
                'request_id' => $consultation->request_id,
                'action' => 'consultation_completed',
                'description' => 'Konsultasi telah diselesaikan.',
            ]);
        });

        return back()->with(
            'success',
            'Konsultasi berhasil diselesaikan.'
        );
    }

    public function cancel(Consultation $consultation): RedirectResponse
    {
        if ($consultation->session_status !== 'scheduled') {
            return back()->withErrors([
                'schedule' => 'Jadwal ini tidak dapat dibatalkan.'
            ]);
        }

        DB::transaction(function () use ($consultation) {

            $consultation->update([
                'session_status' => 'cancelled',
            ]);

            $consultation->consultationRequest()->update([
                'status' => 'cancelled',
            ]);

            ActivityLog::create([
                'user_id' => auth()->id(),
                'request_id' => $consultation->request_id,
                'action' => 'schedule_cancelled',
                'description' => 'Jadwal konsultasi dibatalkan.',
            ]);
        });

        return back()->with(
            'success',
            'Jadwal konsultasi berhasil dibatalkan.'
        );
    }
}