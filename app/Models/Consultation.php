<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Consultation extends Model
{
    protected $fillable = [
        'request_id',
        'counselor_id',
        'scheduled_at',
        'method',
        'meeting_link',
        'location',
        'session_status',
        'follow_up_note',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function consultationRequest(): BelongsTo
    {
        return $this->belongsTo(
            ConsultationRequest::class,
            'request_id'
        );
    }

    public function counselor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'counselor_id');
    }

    public function feedback(): HasOne
    {
        return $this->hasOne(ConsultationFeedback::class);
    }
}