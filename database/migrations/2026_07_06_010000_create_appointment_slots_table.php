<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointment_slots', function (Blueprint $table): void {
            $table->id();
            $table->dateTime('starts_at')->index();
            $table->dateTime('ends_at');
            $table->string('status', 30)->default('available')->index();
            $table->foreignId('appointment_id')->nullable()->constrained()->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointment_slots');
    }
};
