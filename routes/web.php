<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User;
use App\Http\Controllers\Api;
use App\Http\Controllers\Futebol;

Route::get('/', [User::class, 'index'])->name('home');
Route::get('/login', [User::class, 'login'])->name('login');
Route::get('/register', [User::class, 'register'])->name('register');
Route::get('/logout', [User::class, 'logout'])->name('logout');
Route::get('/users', [User::class, 'users'])->name('users');
Route::get('/profile', [User::class, 'profile'])->name('profile');
Route::get('/apis', [Api::class, 'apis'])->name('apis');
Route::get('/execute_api/{id}', [Api::class, 'execute_api'])->name('execute_api');
Route::get('/campeonatos', [Futebol::class, 'campeonatos'])->name('campeonatos');
Route::get('/minhas_previsoes/{rodada?}', [Futebol::class, 'minhas_previsoes'])->name('minhas_previsoes');
Route::get('/classificacao/{rodada?}', [Futebol::class, 'classificacao'])->name('classificacao');
Route::get('/detalhes_partida/{partida_id}', [Futebol::class, 'detalhes_partida'])->name('detalhes_partida');
Route::get('/estatisticas', [Futebol::class, 'estatisticas'])->name('estatisticas');

Route::post('/profile', [User::class, 'profile'])->name('profile');
Route::post('/estatisticas', [Futebol::class, 'estatisticas'])->name('estatisticas');
Route::post('/register_user', [User::class, 'register_user'])->name('register_user');
Route::post('/logar', [User::class, 'logar'])->name('logar');
Route::post('/enviar_previsao', [Futebol::class, 'enviar_previsao'])->name('enviar_previsao');