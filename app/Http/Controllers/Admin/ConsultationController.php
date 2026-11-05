<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConsultationRequest;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ConsultationController extends Controller
{
    public function index(Request $request): View
    {
        $requests = ConsultationRequest::with('consultation')
            ->when($request->filled('search'), function ($query) use ($request) {

                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('ticket_number', 'like', "%{$search}%")
                        ->orWhere('alias', 'like', "%{$search}%");
                });

            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.consultations.index', compact('requests'));
    }

    public function show(ConsultationRequest $consultationRequest): View
    {
        $consultationRequest->load([
            'consultation',
            'aiAssistanceLogs',
        ]);

        return view(
            'admin.consultations.show',
            compact('consultationRequest')
        );
    }

    public function updateStatus(
        Request $request,
        ConsultationRequest $consultationRequest
    ): RedirectResponse {

        $validated = $request->validate([
            'status' => [
                'required',
                'in:reviewing,rejected,referred',
            ],
        ]);

        $allowedTransitions = [
            'pending' => ['reviewing', 'rejected', 'referred'],
            'reviewing' => ['rejected', 'referred'],
        ];

        $currentStatus = $consultationRequest->status;
        $newStatus = $validated['status'];

        if (
            !in_array(
                $newStatus,
                $allowedTransitions[$currentStatus] ?? [],
                true
            )
        ) {
            return back()->withErrors([
                'status' => 'Perubahan status tidak diperbolehkan.',
            ]);
        }

        DB::transaction(function () use ($consultationRequest, $currentStatus, $newStatus) {

            $consultationRequest->update([
                'status' => $newStatus,
            ]);

            ActivityLog::create([
                'user_id' => auth()->id(),
                'request_id' => $consultationRequest->id,
                'action' => 'request_status_updated',
                'description' => "Status diubah dari {$currentStatus} menjadi {$newStatus}.",
            ]);

        });

        return back()->with(
            'success',
            'Status permintaan berhasil diperbarui.'
        );
    }

    public function destroy(
        ConsultationRequest $consultationRequest
    ): RedirectResponse {

        if ($consultationRequest->consultation()->exists()) {
            return back()->withErrors([
                'delete' => 'Permintaan yang sudah memiliki jadwal tidak dapat dihapus melalui menu ini.',
            ]);
        }

        DB::transaction(function () use ($consultationRequest) {

            ActivityLog::create([
                'user_id' => auth()->id(),
                'request_id' => $consultationRequest->id,
                'action' => 'request_archived',
                'description' => 'Permintaan konsultasi diarsipkan.',
            ]);

            $consultationRequest->delete();

        });

        return redirect()
            ->route('admin.consultations.index')
            ->with('success', 'Permintaan berhasil diarsipkan.');
    }
}