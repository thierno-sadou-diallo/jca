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
            'brand_tagline' => 'Immigration, mobilité internationale, recrutement international et coopération internationale',
            'footer_description' => 'Cabinet international de conseil et d’accompagnement spécialisé en immigration, mobilité internationale, recrutement international, coopération internationale et développement durable.',
            'contact_email' => 'contact@jcaconseil.com',
            'contact_phone' => '+221 78 968 51 16',
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

        if (! empty($values['footer_description'])) {
            $values['footer_description'] = str_replace(
                [
                    'Cabinet international de conseil en immigration, mobilite, recrutement et cooperation.',
                    'Cabinet international de conseil et d accompagnement specialise en immigration, mobilite internationale, recrutement international, cooperation internationale et developpement durable.',
                    'Cabinet international de conseil et d’accompagnement specialise en immigration, mobilite internationale, recrutement international, cooperation internationale et developpement durable.',
                ],
                [
                    'Cabinet international de conseil et d’accompagnement spécialisé en immigration, mobilité internationale, recrutement international, coopération internationale et développement durable.',
                    'Cabinet international de conseil et d’accompagnement spécialisé en immigration, mobilité internationale, recrutement international, coopération internationale et développement durable.',
                    'Cabinet international de conseil et d’accompagnement spécialisé en immigration, mobilité internationale, recrutement international, coopération internationale et développement durable.',
                ],
                $values['footer_description'],
            );
        }

        if (! empty($values['brand_tagline'])) {
            $values['brand_tagline'] = str_replace(
                'Immigration, recrutement et cooperation',
                'Immigration, mobilité internationale, recrutement international et coopération internationale',
                $values['brand_tagline'],
            );
        }

        if (($values['footer_signature'] ?? '') === 'Des ponts entre les talents, les organisations et les opportunites.') {
            $values['footer_signature'] = 'Des ponts entre les talents, les organisations et les opportunités.';
        }

        if (blank($values['contact_phone'] ?? '')) {
            $values['contact_phone'] = '+221 78 968 51 16';
        }

        if (($values['contact_phone'] ?? '') === '78 968 51 16') {
            $values['contact_phone'] = '+221 78 968 51 16';
        }

        return $values;
    }
}
