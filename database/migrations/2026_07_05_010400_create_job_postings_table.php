<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_postings', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('company_name')->nullable();
            $table->string('country', 80)->nullable()->index();
            $table->string('city', 120)->nullable();
            $table->string('sector', 120)->nullable()->index();
            $table->string('contract_type', 80)->nullable();
            $table->longText('description')->nullable();
            $table->longText('requirements')->nullable();
            $table->string('status', 30)->default('draft')->index();
            $table->timestamp('published_at')->nullable();
            $table->date('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_postings');
    }
};
