<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_profiles', function (Blueprint $table): void {
            $table->string('type_client', 40)->default('Particulier')->after('account_type')->index();
            $table->string('organization_name')->nullable()->after('city');
        });

        DB::table('user_profiles')
            ->whereNull('type_client')
            ->orWhere('type_client', '')
            ->update(['type_client' => 'Particulier']);
    }

    public function down(): void
    {
        Schema::table('user_profiles', function (Blueprint $table): void {
            $table->dropColumn(['type_client', 'organization_name']);
        });
    }
};
