<?php

use App\Http\Controllers\Api\CupomController;
use App\Http\Controllers\Api\HorariosDisponiveisController;
use App\Http\Controllers\Api\V1\AgendamentoController as ApiAgendamentoController;
use App\Http\Controllers\Api\Webhooks\MercadoPagoController;
use App\Http\Controllers\Api\Webhooks\WhatsappController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.v1.')->group(function () {
    Route::post('/agendamentos', [ApiAgendamentoController::class, 'store'])->name('agendamentos.store');
});

// Validação de cupom (pública)
Route::post('/validar-cupom', [CupomController::class, 'validar'])->name('api.validar-cupom');

// Horários disponíveis (pública)
Route::get('/horarios-disponiveis', [HorariosDisponiveisController::class, 'index'])->name('api.horarios-disponiveis');

Route::post('/webhooks/mercadopago', MercadoPagoController::class)->name('api.webhooks.mercadopago');
Route::post('/webhooks/whatsapp', WhatsappController::class)->name('api.webhooks.whatsapp');
