<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HumanitarianProgram extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'country',
        'focus_area',
        'status',
        'description',
        'image_path',
        'impact_metrics',
    ];

    protected function casts(): array
    {
        return [
            'impact_metrics' => 'array',
        ];
    }
}
