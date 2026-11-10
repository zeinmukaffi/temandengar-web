<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FeedbackReminderDelivery;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\URL;


class InternalFeedbackReminderController extends Controller
{
    /**
     * Verify internal n8n API token.
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
     * Activate the 24-hour timer after invitation email succeeds.
     */
    public function markInvitationSent(
        Request $request,
        FeedbackReminderDelivery $delivery
    ): JsonResponse {

        $this->authorizeN8n($request);

        $consultation = $delivery->consultation;


        // If feedback is already submitted, skip reminder
        if ($consultation->feedback()->exists()) {

            $delivery->update([
                'status' => 'skipped',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Feedback already submitted.',
            ]);

        }


        // Idempotent: do not reset the 24-hour timer
        if (
            in_array($delivery->status, [
                'pending',
                'sent',
            ], true)
        ) {

            return response()->json([
                'success' => true,
                'message' => 'Invitation was already recorded.',
                'due_at' => $delivery->due_at,
            ]);

        }


        if ($delivery->status !== 'awaiting_invitation') {

            return response()->json([
                'success' => false,
                'message' => 'Invalid reminder delivery status.',
            ], 409);

        }


        // Start the timer only after successful email delivery
        $sentAt = now();

        $delivery->update([

            'status' => 'pending',

            'invitation_sent_at' => $sentAt,

            'due_at' => $sentAt->copy()->addHours(24),

        ]);


        return response()->json([

            'success' => true,

            'message' => 'Feedback reminder timer activated.',

            'delivery_id' => $delivery->id,

            'due_at' => $delivery->due_at,

        ]);

    }


    /**
     * Get feedback reminders that are due.
     */
    public function due(Request $request): JsonResponse
    {
        $this->authorizeN8n($request);


        // Remove pending reminders when feedback already exists
        FeedbackReminderDelivery::query()
            ->where('status', 'pending')
            ->whereHas('consultation', function ($query) {
                $query->whereHas('feedback');
            })
            ->update([
                'status' => 'skipped',
            ]);


        // Get eligible pending reminders
        $deliveries = FeedbackReminderDelivery::query()

            ->where('status', 'pending')

            ->whereNotNull('due_at')

            ->where('due_at', '<=', now())

            ->whereHas('consultation', function ($query) {

                $query
                    ->where('session_status', 'completed')
                    ->whereDoesntHave('feedback');

            })

            ->with([
                'consultation.consultationRequest',
            ])

            ->orderBy('due_at')

            ->limit(100)

            ->get();


        // ========================================
        // BUILD RESPONSE DATA
        // ========================================

        $data = $deliveries->map(function ($delivery) {

            $consultation = $delivery->consultation;

            $student = $consultation->consultationRequest;

            if (!$student) {
                return null;
            }


            // Preserve original invitation expiry
            $expiresAt = $delivery->invitation_sent_at
                ->copy()
                ->addDays(30);


            // Do not send an expired feedback link
            if ($expiresAt->isPast()) {

                $delivery->update([
                    'status' => 'skipped',
                ]);

                return null;

            }

            // ========================================
            // GENERATE SIGNED URL USING APP_URL
            // ========================================

            // Gunakan APP_URL agar tidak mengambil hostname
            // dari request n8n (host.docker.internal).

            URL::forceRootUrl(config('app.url'));

            try {

                $feedbackUrl = URL::temporarySignedRoute(

                    'public.feedback.create',

                    $expiresAt,

                    [
                        'consultation' => $consultation->id,
                    ]

                );

            } finally {

                // Kembalikan URL generator ke konfigurasi request normal.
                URL::forceRootUrl(null);

            }

            return [

                'id' => $delivery->id,

                'consultation_id' => $consultation->id,

                'recipient_email' => $delivery->recipient_email,

                'recipient_name' => $delivery->recipient_name,

                'ticket_number' => $student->ticket_number,

                'feedback_url' => $feedbackUrl,

                'expires_at' => $expiresAt->toIso8601String(),

            ];

        })->filter()->values();


        return response()->json([

            'success' => true,

            'total' => $data->count(),

            'data' => $data,

        ]);

    }


    /**
     * Mark feedback reminder as successfully sent.
     */
    public function markSent(
        Request $request,
        FeedbackReminderDelivery $delivery
    ): JsonResponse {

        $this->authorizeN8n($request);


        // Idempotent response
        if ($delivery->status === 'sent') {

            return response()->json([
                'success' => true,
                'message' => 'Reminder already marked as sent.',
            ]);

        }


        // Validate reminder status
        if ($delivery->status !== 'pending') {

            return response()->json([
                'success' => false,
                'message' => 'Reminder is not pending.',
            ], 409);

        }


        $consultation = $delivery->consultation;


        // Check again before marking as sent
        if (
            $consultation->session_status !== 'completed' ||
            $consultation->feedback()->exists()
        ) {

            $delivery->update([
                'status' => 'skipped',
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Feedback already submitted or consultation inactive.',
            ], 409);

        }


        $delivery->update([

            'status' => 'sent',

            'sent_at' => now(),

        ]);


        return response()->json([

            'success' => true,

            'message' => 'Feedback reminder marked as sent.',

            'delivery_id' => $delivery->id,

        ]);

    }

}
