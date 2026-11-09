<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class N8nWebhookService
{
    public function send(
        string $eventType,
        array $data,
        ?string $webhookUrl = null
    ): bool {

        // Jangan kirim jika integrasi dinonaktifkan
        if (!config('services.n8n.enabled')) {
            return false;
        }

        // $url = config('services.n8n.webhook_url');
        $url = $webhookUrl ?: config('services.n8n.webhook_url');
        $token = config('services.n8n.webhook_token');

        // Pastikan URL dan token tersedia
        if (blank($url) || blank($token)) {

            Log::warning(
                'n8n integration configuration is incomplete.',
                ['event_type' => $eventType]
            );

            return false;
        }

        // Generate unique event ID
        $eventId = (string) Str::uuid();

        // Standard event payload
        $payload = [
            'schema_version' => '1.0',

            'event_id' => $eventId,

            'event_type' => $eventType,

            'source' => 'temandengar',

            'occurred_at' => now()->toIso8601String(),

            'data' => $data,
        ];

        try {

            $response = Http::withHeaders([
                'X-TemanDengar-Token' => $token,
            ])
                ->acceptJson()
                ->connectTimeout(3)
                ->timeout(
                    (int) config('services.n8n.timeout', 5)
                )
                ->post($url, $payload);

            if (!$response->successful()) {

                Log::warning(
                    'n8n webhook returned unsuccessful response.',
                    [
                        'event_id' => $eventId,
                        'event_type' => $eventType,
                        'status' => $response->status(),
                    ]
                );

                return false;
            }

            Log::info(
                'n8n webhook accepted.',
                [
                    'event_id' => $eventId,
                    'event_type' => $eventType,
                    'status' => $response->status(),
                ]
            );

            return true;

        } catch (Throwable $exception) {

            // Kegagalan n8n tidak boleh membatalkan pengajuan mahasiswa
            Log::warning(
                'Failed to dispatch n8n webhook.',
                [
                    'event_id' => $eventId,
                    'event_type' => $eventType,
                    'exception' => $exception::class,
                ]
            );

            return false;
        }
    }
}
