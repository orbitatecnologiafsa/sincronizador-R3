<?php

use App\Http\Controllers\LojaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::controller(LojaController::class)->group(function(){
    Route::get('/','cadastro');
    Route::get('/cadastro','cadastro')->name('cadastar');
    Route::get('/atualizar','minhaLoja')->name('atualiziar');
    Route::get('/atualizar/loja','atualizar')->name('atualizar-loja');
    Route::get('/cadastro/loja','cadastrarLoja')->name('cadastro-loja');
});
