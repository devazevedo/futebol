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
        Schema::create('escalacao_por_partida', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('partida_id');
            $table->bigInteger('jogador_id')->nullable();
            $table->bigInteger('treinador_id')->nullable();
            $table->integer('ordem');
            $table->integer('tipo');
            $table->string('time');
            $table->foreign('treinador_id')->references('id')->on('treinadores');
            $table->foreign('jogador_id')->references('id')->on('jogadores');
            $table->foreign('partida_id')->references('id')->on('partidas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('escalacao_por_partida');
    }
};
