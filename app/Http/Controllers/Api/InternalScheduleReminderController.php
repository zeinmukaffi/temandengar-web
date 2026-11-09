<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\ScheduleReminderDelivery;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;

class InternalScheduleReminderController extends Controller
{
    /**
     * Verify that the request comes from authorized n8n.
     */
    private function authorizeN8n(Request $request): void
    {
        $expected = (string) config(
            'services.n8n.internal_api_token'
        );

        $provided = (string) $request->header(
            'X-TemanDengar-Internal-Token'
        );

        abort_unless(
            $expected !== '' &&
            $provided !== '' &&
            hash_equals($expected, $provided),
            401,
            'Unauthorized integration request.'
        );
    }


    /**
     * Generate and return pending schedule reminder deliveries.
     */
    public function due(Request $request): JsonResponse
    {
        $this->authorizeN8n($request);

        $now = Carbon::now('Asia/Jakarta');

        /*
         * Reminder window:
         *
         * 24h = approximately 24 hours before consultation.
         * 1h  = approximately 1 hour before consultation.
         *
         * A 10-minute window accommodates the 5-minute
         * n8n polling interval and small execution delays.
         */

        $windows = [

            '24h' => [
                $now->copy()->addHours(24)->subMinutes(10),
                $now->copy()->addHours(24),
            ],

            '1h' => [
                $now->copy()->addHour()->subMinutes(10),
                $now->copy()->addHour(),
            ],

        ];


        // ========================================
        // GENERATE REMINDER DELIVERY RECORDS
        // ========================================

        foreach ($windows as $reminderType => [$start, $end]) {

            $consultations = Consultation::with([
                'consultationRequest',
                'counselor',
            ])
                ->where('session_status', 'scheduled')
                ->whereBetween('scheduled_at', [
                    $start->format('Y-m-d H:i:s'),
                    $end->format('Y-m-d H:i:s'),
                ])
                ->get();


            foreach ($consultations as $consultation) {

                $student = $consultation->consultationRequest;

                $counselor = $consultation->counselor;


                // Pastikan data penerima tersedia
                if (!$student || !$counselor) {
                    continue;
                }


                $scheduleSnapshot = Carbon::parse(
                    $consultation->scheduled_at,
                    'Asia/Jakarta'
                )->format('Y-m-d H:i:s');


                // ========================================
                // STUDENT REMINDER
                // ========================================

                if (filled($student->email)) {

                    ScheduleReminderDelivery::firstOrCreate(

                        [
                            'consultation_id' => $consultation->id,
                            'scheduled_at' => $scheduleSnapshot,
                            'reminder_type' => $reminderType,
                            'recipient_type' => 'student',
                        ],

                        [
                            'recipient_email' => $student->email,
                            'recipient_name' => $student->alias,
                            'status' => 'pending',
                        ]

                    );

                }


                // ========================================
                // COUNSELOR REMINDER
                // ========================================

                if (filled($counselor->email)) {

                    ScheduleReminderDelivery::firstOrCreate(

                        [
                            'consultation_id' => $consultation->id,
                            'scheduled_at' => $scheduleSnapshot,
                            'reminder_type' => $reminderType,
                            'recipient_type' => 'counselor',
                        ],

                        [
                            'recipient_email' => $counselor->email,
                            'recipient_name' => $counselor->name,
                            'status' => 'pending',
                        ]

                    );

                }

            }

        }


        // ========================================
        // GET PENDING REMINDERS
        // ========================================

        $deliveries = ScheduleReminderDelivery::query()

            ->where('status', 'pending')

            ->whereHas('consultation', function ($query) {

                $query
                    ->where('session_status', 'scheduled')

                    // Jangan kirim reminder dari versi jadwal lama
                    ->whereColumn(
                        'consultations.scheduled_at',
                        'schedule_reminder_deliveries.scheduled_at'
                    );

            })

            ->with([
                'consultation.consultationRequest',
                'consultation.counselor',
            ])

            ->orderBy('id')

            ->limit(100)

            ->get();


        // ========================================
        // FORMAT RESPONSE FOR N8N
        // ========================================

        $data = $deliveries->map(function ($delivery) {

            $consultation = $delivery->consultation;

            $student = $consultation->consultationRequest;

            $counselor = $consultation->counselor;

            $scheduledAt = Carbon::parse(
                $consultation->scheduled_at,
                'Asia/Jakarta'
            )->timezone('Asia/Jakarta');


            $methodLabels = [
                'online' => 'Online',
                'offline' => 'Tatap Muka',
            ];


            return [

                'id' => $delivery->id,

                'reminder_type' => $delivery->reminder_type,

                'reminder_label' => $delivery->reminder_type === '24h'
                    ? 'Besok'
                    : '1 jam lagi',

                'recipient_type' => $delivery->recipient_type,

                'recipient_email' => $delivery->recipient_email,

                'recipient_name' => $delivery->recipient_name,

                'ticket_number' => $student->ticket_number,

                'student_name' => $student->alias,

                'counselor_name' => $counselor->name,

                'scheduled_at' => $scheduledAt->toIso8601String(),

                'scheduled_at_label' =>
                    $scheduledAt->locale('id')
                        ->translatedFormat('d F Y, H:i') . ' WIB',

                'method' => $consultation->method,

                'method_label' => $methodLabels[
                    $consultation->method
                ] ?? $consultation->method,

                'meeting_link' => $consultation->method === 'online'
                    ? $consultation->meeting_link
                    : null,

                'location' => $consultation->method === 'offline'
                    ? $consultation->location
                    : null,

            ];

        })->values();


        return response()->json([

            'success' => true,

            'total' => $data->count(),

            'data' => $data,

        ]);

    }


    /**
     * Mark one reminder delivery as successfully sent.
     */
    public function markSent(
        Request $request,
        ScheduleReminderDelivery $delivery
    ): JsonResponse {

        $this->authorizeN8n($request);


        // Idempotent response
        if ($delivery->status === 'sent') {

            return response()->json([
                'success' => true,
                'message' => 'Reminder already marked as sent.',
            ]);

        }


        $consultation = $delivery->consultation;


        // Pastikan jadwal masih aktif dan belum berubah
        if (
            !$consultation ||
            $consultation->session_status !== 'scheduled' ||
            Carbon::parse($consultation->scheduled_at)->format('Y-m-d H:i:s')
            !== $delivery->scheduled_at->format('Y-m-d H:i:s')
        ) {

            return response()->json([
                'success' => false,
                'message' => 'Schedule is no longer active.',
            ], 409);

        }


        $delivery->update([

            'status' => 'sent',

            'sent_at' => now(),

        ]);


        return response()->json([

            'success' => true,

            'message' => 'Reminder marked as sent.',

            'delivery_id' => $delivery->id,

        ]);

    }
}
