<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{
    use HasFactory;

    public const TYPE_BLOG = 'blog';
    public const TYPE_NEWS = 'news';

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'type',
        'excerpt',
        'body',
        'status',
        'published_at',
    ];

    protected $casts = ['published_at' => 'datetime'];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return array<string, string>
     */
    public static function types(): array
    {
        return [
            self::TYPE_BLOG => 'Blog',
            self::TYPE_NEWS => 'Actualite',
        ];
    }
}
