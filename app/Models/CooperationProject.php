<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CooperationProject extends Model
{
    protected $fillable = [
        'institution_id',
        'title',
        'slug',
        'country',
        'sector',
        'status',
        'starts_at',
        'ends_at',
        'description',
        'image_path',
        'indicators',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'date',
            'ends_at' => 'date',
            'indicators' => 'array',
        ];
    }
}
