<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    public const STATUS_REQUESTED = 'requested';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_REFUSED = 'refused';
    public const STATUS_RESCHEDULED = 'rescheduled';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_COMPLETED = 'completed';

    protected $fillable = [
        'user_id',
        'lead_request_id',
        'topic',
        'starts_at',
        'duration_minutes',
        'channel',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function statuses(): array
    {
        return [
            self::STATUS_REQUESTED => 'Demande',
            self::STATUS_CONFIRMED => 'Confirme',
            self::STATUS_REFUSED => 'Refuse',
            self::STATUS_RESCHEDULED => 'Reporte',
            self::STATUS_CANCELLED => 'Annule',
            self::STATUS_COMPLETED => 'Termine',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function leadRequest(): BelongsTo
    {
        return $this->belongsTo(LeadRequest::class);
    }
}
