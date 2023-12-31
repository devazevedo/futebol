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
        Schema::create('gols', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('partida_id');
            $table->bigInteger('jogador_id');
            $table->string('minuto')->nullable();
            $table->string('periodo');
            $table->boolean('penalti');
            $table->boolean('contra');
            $table->string('time', 50);
            $table->foreign('jogador_id')->references('id')->on('jogadores');
            $table->foreign('partida_id')->references('id')->on('partidas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gols');
    }
};
