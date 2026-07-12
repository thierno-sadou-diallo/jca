<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobPosting extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'company_name',
        'country',
        'city',
        'sector',
        'contract_type',
        'description',
        'requirements',
        'status',
        'published_at',
        'expires_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'expires_at' => 'date',
    ];

    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }
}
