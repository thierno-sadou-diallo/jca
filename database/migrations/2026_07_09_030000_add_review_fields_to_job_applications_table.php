<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_applications', function (Blueprint $table): void {
            $table->foreignId('user_id')->nullable()->after('job_posting_id')->constrained()->nullOnDelete();
            $table->text('admin_note')->nullable()->after('status');
            $table->foreignId('reviewed_by')->nullable()->after('admin_note')->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable()->after('reviewed_by');
        });
    }

    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('reviewed_by');
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn(['admin_note', 'reviewed_at']);
        });
    }
};
