<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class SiteSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'group',
    ];

    /**
     * @return array<string, string>
     */
    public static function defaults(): array
    {
        return [
            'brand_name' => 'JCA',
            'brand_tagline' => 'Immigration et developpement international',
            'footer_description' => 'Cabinet international de conseil et d accompagnement specialise en immigration, mobilite internationale, recrutement international, cooperation internationale et developpement durable.',
            'contact_email' => 'contact@jca-international.com',
            'contact_phone' => '',
            'whatsapp' => '',
            'address' => '',
            'footer_signature' => 'Des ponts entre les talents, les organisations et les opportunites.',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function publicValues(): array
    {
        if (! Schema::hasTable('site_settings')) {
            return self::defaults();
        }

        return array_merge(
            self::defaults(),
            self::query()->pluck('value', 'key')->map(fn ($value) => (string) $value)->all(),
        );
    }
}
