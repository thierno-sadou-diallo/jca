<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CaseStatusHistory extends Model
{
    protected $table = 'case_status_histories';

    protected $fillable = [
        'immigration_case_id',
        'user_id',
        'status',
        'note',
    ];

    public function immigrationCase(): BelongsTo
    {
        return $this->belongsTo(ImmigrationCase::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
