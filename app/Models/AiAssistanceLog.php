<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiAssistanceLog extends Model
{
    protected $fillable = [
        'request_id',
        'counselor_id',
        'task_type',
        'model_name',
        'generated_content',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'reviewed_at' => 'datetime',
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
}