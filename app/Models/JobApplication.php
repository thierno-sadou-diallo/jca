<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobApplication extends Model
{
    use HasFactory;

    public const STATUS_NEW = 'new';
    public const STATUS_IN_REVIEW = 'in_review';
    public const STATUS_PREQUALIFIED = 'prequalified';
    public const STATUS_INTERVIEW = 'interview';
    public const STATUS_ACCEPTED = 'accepted';
    public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'job_posting_id',
        'user_id',
        'name',
        'email',
        'phone',
        'country',
        'resume_path',
        'message',
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
            self::STATUS_NEW => 'Nouvelle',
            self::STATUS_IN_REVIEW => 'En analyse',
            self::STATUS_PREQUALIFIED => 'Prequalifiee',
            self::STATUS_INTERVIEW => 'Entretien',
            self::STATUS_ACCEPTED => 'Acceptee',
            self::STATUS_REJECTED => 'Rejetee',
        ];
    }

    public function jobPosting(): BelongsTo
    {
        return $this->belongsTo(JobPosting::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
