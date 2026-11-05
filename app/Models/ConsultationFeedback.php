<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsultationFeedback extends Model
{
    protected $fillable = [
        'consultation_id',
        'display_name',
        'rating',
        'comment',
        'consent_to_publish',
        'is_published',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'consent_to_publish' => 'boolean',
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function consultation(): BelongsTo
    {
        return $this->belongsTo(Consultation::class);
    }
}