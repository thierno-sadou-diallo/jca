<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('partners', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('type', 80)->nullable()->index();
            $table->string('country', 80)->nullable();
            $table->string('website')->nullable();
            $table->text('summary')->nullable();
            $table->string('logo_path')->nullable();
            $table->boolean('is_featured')->default(false)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partners');
    }
};
