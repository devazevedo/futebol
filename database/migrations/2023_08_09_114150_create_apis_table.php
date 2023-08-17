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
        Schema::create('apis', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('url');
            $table->date('last_execute')->nullable();
            $table->boolean('status');
        });

        DB::table('apis')->insert([
            [
                'name' => 'Listar campeonatos',
                'url' => 'https://api.api-futebol.com.br/v1/campeonatos',
                'last_execute' => null,
                'status' => true,
            ],
            [
                'name' => 'Tabela Brasileirão Série A',
                'url' => 'https://api.api-futebol.com.br/v1/campeonatos/10/tabela',
                'last_execute' => null,
                'status' => true,
            ],
            [
                'name' => 'Partidas Brasileirão Série A',
                'url' => 'https://api.api-futebol.com.br/v1/partidas/',
                'last_execute' => null,
                'status' => true,
            ],
            [
                'name' => 'Partidas por id',
                'url' => 'https://api.api-futebol.com.br/v1/campeonatos/10/tabela',
                'last_execute' => null,
                'status' => true,
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apis');
    }
};
