<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'country',
        'website',
        'summary',
        'logo_path',
        'is_featured',
    ];

    protected $casts = ['is_featured' => 'boolean'];
}
