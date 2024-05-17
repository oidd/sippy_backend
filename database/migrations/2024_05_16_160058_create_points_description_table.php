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
        Schema::create('points_description', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('point_id')->unique();
            $table->boolean('preferable_gender')->nullable();
            $table->timestamp('starts_at')->nullable();
            $table->smallInteger('min_preferable_age')->nullable();
            $table->smallInteger('max_preferable_age')->nullable();

            $table->foreign('point_id')->references('id')->on('points');
        });

        DB::statement('ALTER TABLE points_description
            ADD CONSTRAINT check_min_age_gt_16 CHECK (min_preferable_age >= 16);');

        DB::statement('ALTER TABLE points_description
            ADD CONSTRAINT check_max_age_lt_100 CHECK (max_preferable_age < 100);');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('points_description');
    }
};
