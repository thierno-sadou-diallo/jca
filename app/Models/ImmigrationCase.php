<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ImmigrationCase extends Model
{
    public const STATUS_RECEIVED = 'received';
    public const STATUS_IN_ANALYSIS = 'in_analysis';
    public const STATUS_ADDITIONAL_DOCUMENTS_REQUESTED = 'additional_documents_requested';
    public const STATUS_DOCUMENTS_VALIDATED = 'documents_validated';
    public const STATUS_IN_PROCESS = 'in_process';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'user_id',
        'lead_request_id',
        'reference',
        'program_type',
        'destination_country',
        'status',
        'submitted_at',
        'decision_at',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'date',
            'decision_at' => 'date',
            'metadata' => 'array',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function statuses(): array
    {
        return [
            self::STATUS_RECEIVED => 'Recue',
            self::STATUS_IN_ANALYSIS => 'En analyse',
            self::STATUS_ADDITIONAL_DOCUMENTS_REQUESTED => 'Documents complementaires demandes',
            self::STATUS_DOCUMENTS_VALIDATED => 'Documents valides',
            self::STATUS_IN_PROCESS => 'En traitement',
            self::STATUS_COMPLETED => 'Terminee',
            self::STATUS_REJECTED => 'Rejetee',
        ];
    }

    public static function makeReference(): string
    {
        return 'JCA-IMM-'.now()->format('Ymd').'-'.str_pad((string) (self::count() + 1), 4, '0', STR_PAD_LEFT);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function leadRequest(): BelongsTo
    {
        return $this->belongsTo(LeadRequest::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(CaseStatusHistory::class);
    }
}
