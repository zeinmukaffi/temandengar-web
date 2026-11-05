<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TemanDengarNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $eventType,
        public string $title,
        public string $message,
        public string $url,
        public string $icon = 'bi-bell'
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'event_type' => $this->eventType,

            'title' => $this->title,

            'message' => $this->message,

            'url' => $this->url,

            'icon' => $this->icon,
        ];
    }
}