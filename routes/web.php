<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Site\AgendamentoController;
use App\Http\Controllers\Site\AvaliacaoController;
use App\Http\Controllers\Site\ContaController;
use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Site\PromocaoController;
use App\Http\Controllers\Site\ServicoController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::name('servicos.')->prefix('servicos')->group(function () {
    Route::get('/', [ServicoController::class, 'index'])->name('index');
    Route::get('/{servico}', [ServicoController::class, 'show'])->name('show');
});

Route::middleware('auth')->group(function () {
    Route::get('/agendar', [AgendamentoController::class, 'create'])->name('agendamentos.create');
    Route::post('/agendar', [AgendamentoController::class, 'store'])->name('agendamentos.store');
    Route::get('/agendar/confirmacao', [AgendamentoController::class, 'confirmation'])->name('agendamentos.confirmacao');
    
    Route::get('/minha-conta', [ContaController::class, 'index'])->name('conta.index');
    
    Route::post('/avaliacoes', [AvaliacaoController::class, 'store'])->name('avaliacoes.store');
});

Route::name('promocoes.')->prefix('promocoes')->group(function () {
    Route::get('/', [PromocaoController::class, 'index'])->name('index');
    Route::get('/{slug}', [PromocaoController::class, 'show'])->name('show');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);

    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);

    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

require __DIR__.'/admin.php';
