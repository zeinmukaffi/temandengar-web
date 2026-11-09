<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduleReminderDelivery extends Model
{
    protected $fillable = [
        'consultation_id',
        'scheduled_at',
        'reminder_type',
        'recipient_type',
        'recipient_email',
        'recipient_name',
        'status',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'sent_at' => 'datetime',
        ];
    }

    public function consultation(): BelongsTo
    {
        return $this->belongsTo(
            Consultation::class
        );
    }
}
