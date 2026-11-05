<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'request_id',
        'action',
        'description',
    ];

    public function counselor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function consultationRequest(): BelongsTo
    {
        return $this->belongsTo(
            ConsultationRequest::class,
            'request_id'
        );
    }
}