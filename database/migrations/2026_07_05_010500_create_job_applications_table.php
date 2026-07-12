<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_applications', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('job_posting_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('email');
            $table->string('phone', 40)->nullable();
            $table->string('country', 80)->nullable();
            $table->string('resume_path')->nullable();
            $table->text('message')->nullable();
            $table->string('status', 30)->default('new')->index();
            $table->timestamps();

            $table->index(['email', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
