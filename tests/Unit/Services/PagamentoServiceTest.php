<?php

namespace Tests\Unit\Services;

use App\Models\Agendamento;
use App\Models\Configuracao;
use App\Models\Pagamento;
use App\Services\PagamentoService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PagamentoServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_gera_registro_e_dados_para_pix(): void
    {
        $agendamento = Agendamento::factory()->create([
            'valor_total' => 300,
            'valor_sinal' => 90,
        ]);

        $service = new PagamentoService();

        $resultado = $service->gerarPixQRCode($agendamento);

        $this->assertDatabaseHas('pagamentos', [
            'id' => $resultado['pagamento_id'],
            'agendamento_id' => $agendamento->id,
            'status' => 'PENDING',
            'forma_pagamento' => 'pix',
        ]);

    $pagamento = Pagamento::first();

    $this->assertSame($resultado['pagamento_id'], $pagamento->id);
    $this->assertSame('mercado_pago', $pagamento->gateway);
    $this->assertEquals(90.0, (float) $resultado['valor']);
    $this->assertNotEmpty($resultado['qr_code']);
    }

    public function test_confirma_pagamento_e_atualiza_agendamento(): void
    {
        Carbon::setTestNow('2025-05-01 10:00:00');

        $agendamento = Agendamento::factory()->create([
            'status' => 'PENDENTE',
            'pagamento_confirmado' => false,
            'valor_total' => 200,
            'valor_sinal' => 60,
        ]);

        $pagamento = Pagamento::factory()->create([
            'agendamento_id' => $agendamento->id,
            'status' => 'PENDING',
            'valor_total' => 60,
            'valor_capturado' => null,
            'forma_pagamento' => 'pix',
            'pago_em' => null,
        ]);

        $service = new PagamentoService();

        $service->confirmarPagamento($pagamento->referencia_gateway, [
            'status' => 'paid',
            'valor_capturado' => 60,
        ]);

        $pagamento->refresh();
        $agendamento->refresh();

        $this->assertEquals('PAID', $pagamento->status);
        $this->assertEquals(60.0, (float) $pagamento->valor_capturado);
        $this->assertNotNull($pagamento->pago_em);
        $this->assertTrue($agendamento->pagamento_confirmado);
        $this->assertEquals('CONFIRMADO', $agendamento->status);
    }

    public function test_cobra_taxa_de_cancelamento(): void
    {
        $agendamento = Agendamento::factory()->create([
            'valor_sinal' => 120,
        ]);

        $service = new PagamentoService();

        $cobranca = $service->cobrarTaxaCancelamento($agendamento, 50);

        $this->assertNotNull($cobranca);
        $this->assertEquals(60.0, (float) $cobranca->valor_total);
        $this->assertEquals('DUE', $cobranca->status);
        $this->assertEquals('manual', $cobranca->gateway);
    }

    public function test_nao_regride_status_apos_estorno(): void
    {
        $agendamento = Agendamento::factory()->create([
            'status' => 'CANCELADO',
            'pagamento_confirmado' => false,
        ]);

        $pagamento = Pagamento::factory()->create([
            'agendamento_id' => $agendamento->id,
            'status' => 'REFUNDED',
            'valor_total' => 100,
            'valor_capturado' => 0,
        ]);

        $service = new PagamentoService();

        $resultado = $service->confirmarPagamento($pagamento->referencia_gateway, [
            'status' => 'paid',
            'valor_capturado' => 100,
        ]);

        $pagamento->refresh();
        $agendamento->refresh();

        $this->assertNotNull($resultado);
        $this->assertEquals('REFUNDED', $pagamento->status);
        $this->assertFalse($agendamento->pagamento_confirmado);
        $this->assertEquals('REFUNDED', $resultado->status);
        $this->assertEquals(0.0, (float) $pagamento->valor_capturado);
    }

    public function test_taxa_cancelamento_utiliza_configuracao(): void
    {
        Configuracao::create([
            'chave' => 'taxa_cancelamento_percentual',
            'valor' => ['valor' => 25],
        ]);

        $agendamento = Agendamento::factory()->create([
            'valor_sinal' => 80,
        ]);

        $service = new PagamentoService();

        $cobranca = $service->cobrarTaxaCancelamento($agendamento);

        $this->assertNotNull($cobranca);
        $this->assertEquals(20.0, (float) $cobranca->valor_total);
        $this->assertEquals(25.0, (float) $cobranca->payload['percentual_aplicado']);
    }
}
