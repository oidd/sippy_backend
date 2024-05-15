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
        Schema::create('chunk_point', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('point_id');
            $table->unsignedBigInteger('chunk_id');
            $table->timestamps();

            $table->foreign('point_id')->references('id')->on('points');
            $table->foreign('chunk_id')->references('id')->on('chunks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chunk_point');
    }
};
