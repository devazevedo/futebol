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
        Schema::create('estatisticas', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('partida_id');
            $table->bigInteger('time_id');
            $table->integer('placar');
            $table->string('posse_de_bola');
            $table->integer('escanteios');
            $table->integer('impedimentos');
            $table->integer('faltas');
            $table->integer('passes_total');
            $table->integer('passes_completos');
            $table->integer('passes_errados');
            $table->string('passe_precisao');
            $table->integer('finalizacao_total');
            $table->integer('finalizacao_no_gol');
            $table->integer('finalizacao_pra_fora');
            $table->integer('finalizacao_na_trave');
            $table->integer('finalizacao_bloqueado');
            $table->string('finalizacao_precisao');
            $table->integer('cartoes_amarelo');
            $table->integer('cartoes_vermelho');
            $table->integer('defesas');
            $table->integer('desarmes');
            $table->string('esquema_tatico');
            $table->foreign('time_id')->references('id')->on('times');
            $table->foreign('partida_id')->references('id')->on('partidas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estatisticas');
    }
};
