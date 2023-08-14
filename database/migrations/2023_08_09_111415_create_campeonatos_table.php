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
        Schema::create('campeonatos', function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->string('name', 50);
            $table->string('popular_name', 50);
            $table->integer('rodada_atual');
            $table->string('season', 10);
            $table->string('round', 50)->nullable();
            $table->string('phase', 50)->nullable();
            $table->string('status');
            $table->string('logo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campeonatos');
    }
};
