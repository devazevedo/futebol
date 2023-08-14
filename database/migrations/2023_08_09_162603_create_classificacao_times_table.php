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
        Schema::create('classificacao_times', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('time_id');
            $table->bigInteger('campeonato_id');
            $table->integer('posicao');
            $table->integer('pontos');
            $table->integer('jogos');
            $table->integer('vitorias');
            $table->integer('empates');
            $table->integer('derrotas');
            $table->integer('gols_pro');
            $table->integer('gols_contra');
            $table->integer('saldo_gols');
            $table->integer('aproveitamento');
            $table->integer('variacao_posicao');
            $table->string('ultimos_jogos');
            $table->foreign('time_id')->references('id')->on('times');
            $table->foreign('campeonato_id')->references('id')->on('campeonatos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classificacao_times');
    }
};
