<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\TemanDengarNotification;

class CounselorNotificationService
{
    public function notifyCounselors(
        string $eventType,
        string $title,
        string $message,
        string $url,
        string $icon = 'bi-bell'
    ): void {

        // Semua akun User pada versi ini merupakan akun konselor.
        // Mahasiswa tidak memiliki akun login.

        User::query()->each(function (User $counselor) use ($eventType, $title, $message, $url, $icon) {

            $counselor->notify(
                new TemanDengarNotification(
                    $eventType,
                    $title,
                    $message,
                    $url,
                    $icon
                )
            );

        });
    }
}