<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('testimonials', function (Blueprint $table): void {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->text('admin_response')->nullable()->after('quote');
            $table->timestamp('responded_at')->nullable()->after('admin_response');
            $table->string('status', 30)->default('pending')->after('is_published')->index();
        });
    }

    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn(['admin_response', 'responded_at', 'status']);
        });
    }
};
