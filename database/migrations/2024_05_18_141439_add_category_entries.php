<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //пабы, рестораны
        //вечер дома
        //кино
        //сабантуй

        DB::unprepared("INSERT INTO category (id, name)
            VALUES ('pubs', 'пабы, рестораны'),
                   ('at_home', 'вечер дома'),
                   ('cinema', 'кино'),
                   ('sabantui', 'сабантуй')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
