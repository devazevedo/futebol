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
        Schema::create('previsoes', function (Blueprint $table) {
            $table->id();
            $table->integer('placar_mandante');
            $table->integer('placar_visitante');
            $table->integer('rodada');
            $table->bigInteger('partida_id');
            $table->unsignedBigInteger('user_id');
            $table->string('status');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('partida_id')->references('id')->on('partidas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('previsoes');
    }
};
