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
            'brand_tagline' => 'Immigration, recrutement et cooperation',
            'footer_description' => 'Cabinet international de conseil en immigration, mobilite, recrutement et cooperation.',
            'contact_email' => 'contact@jcaconseil.com',
            'contact_phone' => '78 968 51 16',
            'whatsapp' => '',
            'address' => '',
            'footer_signature' => 'Des ponts entre les talents, les organisations et les opportunités.',
            'collaboration_document_path' => '',
            'collaboration_document_name' => '',
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

        $values = array_merge(
            self::defaults(),
            self::query()->pluck('value', 'key')->map(fn ($value) => (string) $value)->all(),
        );

        if (($values['contact_email'] ?? '') === 'contact@jca-international.com') {
            $values['contact_email'] = 'contact@jcaconseil.com';
        }

        if (blank($values['contact_phone'] ?? '')) {
            $values['contact_phone'] = '78 968 51 16';
        }

        return $values;
    }
}
