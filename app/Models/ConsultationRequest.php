<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ConsultationRequest extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'ticket_number',
        'alias',
        'email',
        'phone',
        'topic',
        'description',
        'preferred_method',
        'preferred_date',
        'preferred_time',
        'status',
        'consent_at',
    ];

    protected function casts(): array
    {
        return [
            'preferred_date' => 'date',
            'consent_at' => 'datetime',
        ];
    }

    public function consultation(): HasOne
    {
        return $this->hasOne(Consultation::class, 'request_id');
    }

    public function aiAssistanceLogs(): HasMany
    {
        return $this->hasMany(AiAssistanceLog::class, 'request_id');
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class, 'request_id');
    }
}