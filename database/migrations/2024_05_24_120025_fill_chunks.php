<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        \Illuminate\Support\Facades\DB::unprepared(file_get_contents(__DIR__ . '/../../dmp.sql'));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
