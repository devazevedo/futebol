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
        Schema::create('substituicoes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('partida_id');
            $table->bigInteger('saiu');
            $table->bigInteger('entrou');
            $table->string('periodo');
            $table->string('minuto')->nullable();
            $table->foreign('saiu')->references('id')->on('jogadores');
            $table->foreign('entrou')->references('id')->on('jogadores');
            $table->foreign('partida_id')->references('id')->on('partidas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('substituicoes');
    }
};
