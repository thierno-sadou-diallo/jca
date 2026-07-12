<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table): void {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('group', 60)->default('general')->index();
            $table->timestamps();
        });

        foreach ([
            'brand_name' => ['JCA', 'general'],
            'brand_tagline' => ['Immigration et developpement international', 'general'],
            'footer_description' => ['Cabinet international de conseil et d accompagnement specialise en immigration, mobilite internationale, recrutement international, cooperation internationale et developpement durable.', 'general'],
            'contact_email' => ['contact@jca-international.com', 'contact'],
            'contact_phone' => ['', 'contact'],
            'whatsapp' => ['', 'contact'],
            'address' => ['', 'contact'],
            'footer_signature' => ['Des ponts entre les talents, les organisations et les opportunites.', 'general'],
        ] as $key => [$value, $group]) {
            DB::table('site_settings')->insert([
                'key' => $key,
                'value' => $value,
                'group' => $group,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
