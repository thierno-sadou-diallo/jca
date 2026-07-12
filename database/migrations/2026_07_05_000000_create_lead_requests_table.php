<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lead_requests', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 120);
            $table->string('email', 160);
            $table->string('phone', 40)->nullable();
            $table->string('topic', 80);
            $table->text('message');
            $table->string('source', 60)->nullable();
            $table->string('page_slug', 80)->nullable();
            $table->date('preferred_date')->nullable();
            $table->string('preferred_channel', 30)->nullable();
            $table->string('status', 30)->default('new')->index();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->json('payload')->nullable();
            $table->timestamps();

            $table->index(['topic', 'created_at']);
            $table->index(['page_slug', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lead_requests');
    }
};
