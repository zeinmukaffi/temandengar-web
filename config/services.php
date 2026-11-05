<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'n8n' => [
        'enabled' => env('N8N_ENABLED', false),
        'webhook_url' => env('N8N_WEBHOOK_URL'),
        'webhook_token' => env('N8N_WEBHOOK_TOKEN'),
        'timeout' => (int) env('N8N_TIMEOUT', 5),
    ],

    'hermes' => [
        'enabled' => env('HERMES_ENABLED', false),
        'base_url' => env('HERMES_BASE_URL'),
        'api_token' => env('HERMES_API_TOKEN'),
        'timeout' => (int) env('HERMES_TIMEOUT', 30),
    ],

];
