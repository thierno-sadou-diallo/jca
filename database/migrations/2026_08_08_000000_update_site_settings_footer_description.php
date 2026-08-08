<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('site_settings')
            ->where('key', 'footer_description')
            ->where('value', 'Cabinet international de conseil en immigration, mobilite, recrutement et cooperation.')
            ->update([
                'value' => 'Cabinet international de conseil et d’accompagnement spécialisé en immigration, mobilité internationale, recrutement international, coopération internationale et développement durable.',
            ]);
    }

    public function down(): void
    {
        DB::table('site_settings')
            ->where('key', 'footer_description')
            ->where('value', 'Cabinet international de conseil et d’accompagnement spécialisé en immigration, mobilité internationale, recrutement international, coopération internationale et développement durable.')
            ->update([
                'value' => 'Cabinet international de conseil en immigration, mobilite, recrutement et cooperation.',
            ]);
    }
};
