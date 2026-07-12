<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProfile extends Model
{
    public const TYPE_PARTICULIER = 'Particulier';
    public const TYPE_CANDIDAT = 'Candidat';
    public const TYPE_ENTREPRISE = 'Entreprise';
    public const TYPE_ONG = 'ONG';
    public const TYPE_INSTITUTION = 'Institution';
    public const TYPE_PARTENAIRE = 'Partenaire';

    protected $fillable = [
        'user_id',
        'account_type',
        'type_client',
        'country',
        'city',
        'organization_name',
        'preferred_language',
        'preferences',
    ];

    protected function casts(): array
    {
        return [
            'preferences' => 'array',
        ];
    }

    /**
     * @return array<int, string>
     */
    public static function clientTypes(): array
    {
        return [
            self::TYPE_PARTICULIER,
            self::TYPE_CANDIDAT,
            self::TYPE_ENTREPRISE,
            self::TYPE_ONG,
            self::TYPE_INSTITUTION,
            self::TYPE_PARTENAIRE,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
