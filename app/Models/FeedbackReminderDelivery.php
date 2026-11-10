<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeedbackReminderDelivery extends Model
{
    protected $fillable = [
        'consultation_id',
        'recipient_email',
        'recipient_name',
        'status',
        'invitation_sent_at',
        'due_at',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'invitation_sent_at' => 'datetime',
            'due_at' => 'datetime',
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
