<?php

use App\Http\Controllers\Admin\AgendaController;
use App\Http\Controllers\Admin\AgendamentoController as AdminAgendamentoController;
use App\Http\Controllers\Admin\AvaliacaoController;
use App\Http\Controllers\Admin\ClienteController;
use App\Http\Controllers\Admin\ConfiguracaoController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FinanceiroController;
use App\Http\Controllers\Admin\PromocaoController;
use App\Http\Controllers\Admin\RelatorioController;
use App\Http\Controllers\Admin\ServicoController as AdminServicoController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/agenda', [AgendaController::class, 'index'])->name('agenda.index');
        Route::get('/agenda/semanal', [AgendaController::class, 'semanal'])->name('agenda.semanal');

        Route::get('/agendamentos', [AdminAgendamentoController::class, 'index'])->name('agendamentos.index');
        Route::post('/agendamentos', [AdminAgendamentoController::class, 'store'])->name('agendamentos.store');
        Route::get('/agendamentos/criar', [AdminAgendamentoController::class, 'create'])->name('agendamentos.create');
        Route::get('/agendamentos/{agendamento}', [AdminAgendamentoController::class, 'show'])->name('agendamentos.show');
        Route::get('/agendamentos/{agendamento}/edit', [AdminAgendamentoController::class, 'edit'])->name('agendamentos.edit');
        Route::put('/agendamentos/{agendamento}', [AdminAgendamentoController::class, 'update'])->name('agendamentos.update');
        Route::delete('/agendamentos/{agendamento}', [AdminAgendamentoController::class, 'destroy'])->name('agendamentos.destroy');
        Route::put('/agendamentos/{agendamento}/status', [AdminAgendamentoController::class, 'updateStatus'])->name('agendamentos.status');

        Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
        Route::get('/clientes/{cliente}', [ClienteController::class, 'show'])->name('clientes.show');
        Route::put('/clientes/{cliente}', [ClienteController::class, 'update'])->name('clientes.update');
        Route::delete('/clientes/{cliente}', [ClienteController::class, 'destroy'])->name('clientes.destroy');

        Route::resource('servicos', AdminServicoController::class)->except(['show']);
        Route::resource('promocoes', PromocaoController::class)->except(['show'])->parameters(['promocoes' => 'promocao']);

        Route::get('/avaliacoes', [AvaliacaoController::class, 'index'])->name('avaliacoes.index');
        Route::get('/avaliacoes/{avaliacao}', [AvaliacaoController::class, 'show'])->name('avaliacoes.show');
        Route::put('/avaliacoes/{avaliacao}', [AvaliacaoController::class, 'update'])->name('avaliacoes.update');
        Route::delete('/avaliacoes/{avaliacao}', [AvaliacaoController::class, 'destroy'])->name('avaliacoes.destroy');
        Route::post('/avaliacoes/publicar-lote', [AvaliacaoController::class, 'publicarEmLote'])->name('avaliacoes.publicar-lote');

        Route::get('/financeiro', [FinanceiroController::class, 'index'])->name('financeiro.index');

        Route::get('/relatorios', [RelatorioController::class, 'index'])->name('relatorios.index');

        Route::get('/configuracoes', [ConfiguracaoController::class, 'index'])->name('configuracoes.index');
        Route::put('/configuracoes', [ConfiguracaoController::class, 'update'])->name('configuracoes.update');
    });
