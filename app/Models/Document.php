<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_VALIDATED = 'validated';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_NEW_VERSION_REQUESTED = 'new_version_requested';

    protected $fillable = [
        'user_id',
        'lead_request_id',
        'title',
        'type',
        'path',
        'visibility',
        'status',
        'admin_note',
        'reviewed_by',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'reviewed_at' => 'datetime',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function statuses(): array
    {
        return [
            self::STATUS_PENDING => 'En attente',
            self::STATUS_VALIDATED => 'Valide',
            self::STATUS_REJECTED => 'Rejete',
            self::STATUS_NEW_VERSION_REQUESTED => 'Nouvelle version demandee',
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

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
