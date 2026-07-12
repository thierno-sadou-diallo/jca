<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('role', 30)->default('client')->after('password')->index();
            $table->string('status', 30)->default('active')->after('role')->index();
            $table->string('phone', 40)->nullable()->after('email');
            $table->timestamp('last_login_at')->nullable()->after('remember_token');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn(['role', 'status', 'phone', 'last_login_at']);
        });
    }
};
