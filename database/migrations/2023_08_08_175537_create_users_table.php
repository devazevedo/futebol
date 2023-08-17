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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email', 50);
            $table->string('password', 60);
            $table->string('name', 50);
            $table->string('lastname', 50);
            $table->string('cpf', 11);
            $table->string('phone', 14);
            $table->boolean('admin')->default(0);
            $table->boolean('previsao_paga')->default(0);
            $table->float('saldo')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
