<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\ConsultationRequest;
use App\Models\ActivityLog;
use App\Services\N8nWebhookService;
use App\Services\CounselorNotificationService;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Carbon;

class ScheduleController extends Controller
{
    /**
     * Display consultation schedules.
     */
    public function index(Request $request): View
    {
        $schedules = Consultation::with([
            'consultationRequest',
            'counselor',
        ])
            ->when($request->filled('search'), function ($query) use ($request) {

                $search = $request->search;

                $query->whereHas(
                    'consultationRequest',
                    function ($q) use ($search) {

                        $q->where('ticket_number', 'like', "%{$search}%")
                            ->orWhere('alias', 'like', "%{$search}%");
                    }
                );

            })
            ->when($request->filled('status'), function ($query) use ($request) {

                $query->where('session_status', $request->status);

            })
            ->orderByDesc('scheduled_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.schedules.index', compact('schedules'));
    }


    /**
     * Display schedule creation form.
     */
    public function create(
        ConsultationRequest $consultationRequest
    ): View|RedirectResponse {

        if (
            !in_array(
                $consultationRequest->status,
                ['pending', 'reviewing'],
                true
            )
        ) {
            abort(403, 'Permintaan ini tidak dapat dijadwalkan.');
        }

        if ($consultationRequest->consultation()->exists()) {

            return redirect()
                ->route('admin.consultations.show', $consultationRequest)
                ->withErrors([
                    'schedule' => 'Permintaan ini sudah memiliki jadwal.',
                ]);
        }

        return view(
            'admin.schedules.create',
            compact('consultationRequest')
        );
    }


    /**
     * Store a new consultation schedule.
     */
    public function store(
        Request $request,
        ConsultationRequest $consultationRequest
    ): RedirectResponse {

        // Pastikan permintaan dapat dijadwalkan
        if (
            !in_array(
                $consultationRequest->status,
                ['pending', 'reviewing'],
                true
            )
        ) {
            abort(403, 'Permintaan ini tidak dapat dijadwalkan.');
        }

        // Cegah pembuatan jadwal ganda
        if ($consultationRequest->consultation()->exists()) {

            return back()->withErrors([
                'schedule' => 'Permintaan ini sudah memiliki jadwal.',
            ]);
        }

        // Validasi input jadwal
        $validated = $request->validate([

            'scheduled_at' => [
                'required',
                'date',
                'after:now',
            ],

            'method' => [
                'required',
                Rule::in(['online', 'offline']),
            ],

            'meeting_link' => [
                'nullable',
                'url',
                'max:255',
            ],

            'location' => [
                'nullable',
                'required_if:method,offline',
                'string',
                'max:255',
            ],

        ]);

        // Normalisasi data berdasarkan metode konsultasi
        $validated['meeting_link'] = $validated['method'] === 'online'
            ? ($validated['meeting_link'] ?? null)
            : null;

        $validated['location'] = $validated['method'] === 'offline'
            ? ($validated['location'] ?? null)
            : null;


        // Simpan konsultasi dan perubahan status dalam transaksi
        $consultation = DB::transaction(function () use ($validated, $consultationRequest) {

            $consultation = $consultationRequest->consultation()->create([

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

            // Kembalikan model Consultation
            return $consultation;

        });


        // ========================================
        // INTERNAL NOTIFICATION
        // ========================================

        app(CounselorNotificationService::class)
            ->notifyCounselors(

                'schedule_created',

                'Jadwal konsultasi dibuat',

                'Jadwal konsultasi untuk tiket '
                . $consultationRequest->ticket_number
                . ' berhasil dibuat.',

                route(
                    'admin.consultations.show',
                    $consultationRequest,
                    false
                ),

                'bi-calendar-plus'

            );


        // ========================================
        // WF-02: N8N EMAIL AUTOMATION
        // ========================================

        $this->notifyScheduleEvent(
            $consultation,
            'consultation.scheduled'
        );


        return redirect()
            ->route('admin.consultations.show', $consultationRequest)
            ->with('success', 'Jadwal konsultasi berhasil dibuat.');
    }


    /**
     * Display schedule edit form.
     */
    public function edit(
        Consultation $consultation
    ): View {

        if ($consultation->session_status !== 'scheduled') {
            abort(403, 'Jadwal ini tidak dapat diedit.');
        }

        return view(
            'admin.schedules.edit',
            compact('consultation')
        );
    }


    /**
     * Update consultation schedule.
     */
    public function update(
        Request $request,
        Consultation $consultation
    ): RedirectResponse {

        if ($consultation->session_status !== 'scheduled') {

            abort(403, 'Jadwal ini tidak dapat diedit.');

        }


        // Validasi input
        $validated = $request->validate([

            'scheduled_at' => [
                'required',
                'date',
                'after:now',
            ],

            'method' => [
                'required',
                Rule::in(['online', 'offline']),
            ],

            'meeting_link' => [
                'nullable',
                'url',
                'max:255',
            ],

            'location' => [
                'nullable',
                'required_if:method,offline',
                'string',
                'max:255',
            ],

        ]);


        // Normalisasi link dan lokasi
        $validated['meeting_link'] = $validated['method'] === 'online'
            ? ($validated['meeting_link'] ?? null)
            : null;

        $validated['location'] = $validated['method'] === 'offline'
            ? ($validated['location'] ?? null)
            : null;


        // Simpan perubahan jadwal
        DB::transaction(function () use ($validated, $consultation) {

            $consultation->update($validated);

            ActivityLog::create([

                'user_id' => auth()->id(),

                'request_id' => $consultation->request_id,

                'action' => 'schedule_updated',

                'description' => 'Jadwal konsultasi diperbarui.',

            ]);

        });


        // Refresh data setelah transaksi berhasil
        $consultation->refresh();


        // INTERNAL NOTIFICATION
        app(CounselorNotificationService::class)
            ->notifyCounselors(

                'schedule_updated',

                'Jadwal konsultasi diperbarui',

                'Jadwal konsultasi untuk tiket '
                . $consultation->consultationRequest->ticket_number
                . ' telah diperbarui.',

                route(
                    'admin.consultations.show',
                    $consultation->consultationRequest,
                    false
                ),

                'bi-calendar-event'

            );


        // WF-02: RESCHEDULED EMAIL
        $this->notifyScheduleEvent(
            $consultation,
            'consultation.rescheduled'
        );


        return redirect()
            ->route('admin.schedules.index')
            ->with('success', 'Jadwal berhasil diperbarui.');
    }


    /**
     * Mark consultation as completed.
     *
     * External feedback automation will be handled by WF-04.
     */
    public function complete(
        Consultation $consultation
    ): RedirectResponse {

        if ($consultation->session_status !== 'scheduled') {

            return back()->withErrors([
                'schedule' => 'Jadwal ini tidak dapat diselesaikan.',
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


        // INTERNAL NOTIFICATION
        app(CounselorNotificationService::class)
            ->notifyCounselors(

                'consultation_completed',

                'Konsultasi telah selesai',

                'Sesi konsultasi untuk tiket '
                . $consultation->consultationRequest->ticket_number
                . ' telah diselesaikan.',

                route(
                    'admin.consultations.show',
                    $consultation->consultationRequest,
                    false
                ),

                'bi-check-circle'

            );


        return back()->with(
            'success',
            'Konsultasi berhasil diselesaikan.'
        );
    }


    /**
     * Cancel consultation schedule.
     */
    public function cancel(
        Consultation $consultation
    ): RedirectResponse {

        if ($consultation->session_status !== 'scheduled') {

            return back()->withErrors([
                'schedule' => 'Jadwal ini tidak dapat dibatalkan.',
            ]);

        }


        // Simpan perubahan status
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


        // Refresh data terbaru
        $consultation->refresh();


        // INTERNAL NOTIFICATION
        app(CounselorNotificationService::class)
            ->notifyCounselors(

                'schedule_cancelled',

                'Jadwal konsultasi dibatalkan',

                'Jadwal konsultasi untuk tiket '
                . $consultation->consultationRequest->ticket_number
                . ' telah dibatalkan.',

                route(
                    'admin.consultations.show',
                    $consultation->consultationRequest,
                    false
                ),

                'bi-calendar-x'

            );


        // WF-02: CANCELLATION EMAIL
        $this->notifyScheduleEvent(
            $consultation,
            'consultation.cancelled'
        );


        return back()->with(
            'success',
            'Jadwal konsultasi berhasil dibatalkan.'
        );
    }


    /**
     * Send schedule event to n8n WF-02.
     *
     * Supported events:
     * - consultation.scheduled
     * - consultation.rescheduled
     * - consultation.cancelled
     */
    private function notifyScheduleEvent(
        Consultation $consultation,
        string $eventType
    ): void {

        // ========================================
        // GET RELATED DATA
        // ========================================

        $consultation->loadMissing([
            'consultationRequest',
            'counselor',
        ]);

        $student = $consultation->consultationRequest;

        $counselor = $consultation->counselor;


        // ========================================
        // VALIDATE RECIPIENTS
        // ========================================

        if (
            !$student ||
            !$counselor ||
            blank($student->email) ||
            blank($counselor->email)
        ) {

            Log::warning(
                'Schedule notification skipped.',
                [
                    'consultation_id' => $consultation->id,
                    'event_type' => $eventType,
                    'reason' => 'Missing student or counselor recipient.',
                ]
            );

            return;
        }


        // ========================================
        // CHECK N8N CONFIGURATION
        // ========================================

        $scheduleWebhookUrl = config(
            'services.n8n.schedule_webhook_url'
        );

        if (blank($scheduleWebhookUrl)) {

            Log::warning(
                'Schedule webhook URL is not configured.',
                [
                    'consultation_id' => $consultation->id,
                    'event_type' => $eventType,
                ]
            );

            return;
        }


        // ========================================
        // FORMAT SCHEDULE DATA
        // ========================================

        $scheduledAt = Carbon::parse(
            $consultation->scheduled_at
        )->timezone('Asia/Jakarta');


        $methodLabels = [
            'online' => 'Online',
            'offline' => 'Tatap Muka',
        ];


        // Meeting link hanya disertakan saat jadwal aktif.
        // Ketika dibatalkan, link dan lokasi tidak diperlukan.
        $isCancelled = $eventType === 'consultation.cancelled';


        $meetingLink = (
            !$isCancelled &&
            $consultation->method === 'online'
        )
            ? $consultation->meeting_link
            : null;


        $location = (
            !$isCancelled &&
            $consultation->method === 'offline'
        )
            ? $consultation->location
            : null;


        // ========================================
        // BUILD EVENT PAYLOAD
        // ========================================

        $payload = [

            'consultation_id' => $consultation->id,

            'ticket_number' => $student->ticket_number,

            'student' => [

                'display_name' => $student->alias,

                'email' => $student->email,

            ],

            'counselor' => [

                'name' => $counselor->name,

                'email' => $counselor->email,

            ],

            'schedule' => [

                'scheduled_at' => $scheduledAt->toIso8601String(),

                'scheduled_at_label' =>
                    $scheduledAt->locale('id')
                        ->translatedFormat('d F Y, H:i')
                    . ' WIB',

                'method' => $consultation->method,

                'method_label' => $methodLabels[
                    $consultation->method
                ] ?? $consultation->method,

                'meeting_link' => $meetingLink,

                'location' => $location,

            ],

        ];


        // ========================================
        // SEND EVENT TO N8N WF-02
        // ========================================

        app(N8nWebhookService::class)->send(

            $eventType,

            $payload,

            $scheduleWebhookUrl

        );
    }
}
