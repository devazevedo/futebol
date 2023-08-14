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
        Schema::create('partidas', function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->bigInteger('campeonato_id');
            $table->bigInteger('id_time_mandante');
            $table->bigInteger('id_time_visitante');
            $table->integer('rodada');
            $table->string('estadio')->nullable();
            $table->string('data_realizacao')->nullable();
            $table->string('hora_realizacao')->nullable();
            $table->string('status');
            $table->foreign('id_time_mandante')->references('id')->on('times');
            $table->foreign('id_time_visitante')->references('id')->on('times');
            $table->foreign('campeonato_id')->references('id')->on('campeonatos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partidas');
    }
};
