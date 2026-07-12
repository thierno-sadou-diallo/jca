<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('immigration_cases')
            ->where('status', 'new')
            ->update(['status' => 'received']);
    }

    public function down(): void
    {
        DB::table('immigration_cases')
            ->where('status', 'received')
            ->update(['status' => 'new']);
    }
};
