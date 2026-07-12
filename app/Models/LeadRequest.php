<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeadRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'topic',
        'message',
        'source',
        'page_slug',
        'preferred_date',
        'preferred_channel',
        'status',
        'ip_address',
        'user_agent',
        'payload',
    ];

    protected $casts = [
        'preferred_date' => 'date',
        'payload' => 'array',
    ];

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }
}
