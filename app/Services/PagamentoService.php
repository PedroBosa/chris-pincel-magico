<?php

namespace App\Services;

use App\Models\Agendamento;
use App\Models\Configuracao;
use App\Models\Pagamento;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PagamentoService
{
    /**
     * Cria um registro de pagamento aguardando captura e retorna dados para exibição do QRCode PIX.
     */
    public function gerarPixQRCode(Agendamento $agendamento): array
    {
        $valor = $agendamento->valor_sinal > 0 ? $agendamento->valor_sinal : $agendamento->valor_total;

        $pagamento = Pagamento::query()->create([
            'agendamento_id' => $agendamento->getKey(),
            'gateway' => 'mercado_pago',
            'referencia_gateway' => (string) Str::uuid(),
            'status' => 'PENDING',
            'valor_total' => $valor,
            'valor_capturado' => null,
            'forma_pagamento' => 'pix',
            'payload' => null,
            'pago_em' => null,
        ]);

        return [
            'pagamento_id' => $pagamento->getKey(),
            'referencia' => $pagamento->referencia_gateway,
            'valor' => $valor,
            'qr_code' => $this->gerarCodigoPixFake($pagamento),
            'qr_code_base64' => null,
        ];
    }

    /**
     * Atualiza o pagamento com dados do webhook de confirmação.
     */
    public function confirmarPagamento(string $referenciaGateway, array $payload = []): ?Pagamento
    {
        /** @var Pagamento|null $pagamento */
        $pagamento = Pagamento::query()
            ->where('referencia_gateway', $referenciaGateway)
            ->lockForUpdate()
            ->first();

        if (! $pagamento) {
            return null;
        }

        DB::transaction(function () use ($pagamento, $payload) {
            $valorCapturado = Arr::get($payload, 'valor_capturado', $pagamento->valor_total);

            $pagamento->update([
                'status' => strtoupper(Arr::get($payload, 'status', 'PAID')),
                'valor_capturado' => $valorCapturado,
                'payload' => $payload,
                'pago_em' => Carbon::now(),
            ]);

            $pagamento->agendamento->update([
                'pagamento_confirmado' => true,
                'status' => $pagamento->agendamento->status === 'PENDENTE' ? 'CONFIRMADO' : $pagamento->agendamento->status,
            ]);
        });

        return $pagamento->fresh(['agendamento']);
    }

    /**
     * Registra um estorno simples no histórico do pagamento.
     */
    public function estornarPagamento(Pagamento $pagamento, float $valor): Pagamento
    {
        $payload = $pagamento->payload ?? [];
        $historico = Arr::get($payload, 'refunds', []);
        $historico[] = [
            'valor' => $valor,
            'data' => Carbon::now()->toISOString(),
        ];

        $pagamento->update([
            'status' => 'REFUNDED',
            'valor_capturado' => max(0, ($pagamento->valor_capturado ?? $pagamento->valor_total) - $valor),
            'payload' => array_merge($payload, ['refunds' => $historico]),
        ]);

        return $pagamento->fresh();
    }

    /**
     * Gera registro de cobrança para taxa de cancelamento quando aplicável.
     */
    public function cobrarTaxaCancelamento(Agendamento $agendamento, ?float $percentualOverride = null): ?Pagamento
    {
        $percentual = $percentualOverride ?? Arr::get(Configuracao::get('taxa_cancelamento_percentual', ['valor' => 100]), 'valor', 100);
        $valor = round($agendamento->valor_sinal * ($percentual / 100), 2);

        if ($valor <= 0) {
            return null;
        }

        return Pagamento::query()->create([
            'agendamento_id' => $agendamento->getKey(),
            'gateway' => 'manual',
            'referencia_gateway' => (string) Str::uuid(),
            'status' => 'DUE',
            'valor_total' => $valor,
            'valor_capturado' => 0,
            'forma_pagamento' => 'manual',
            'payload' => [
                'tipo' => 'taxa_cancelamento',
                'percentual_aplicado' => $percentual,
            ],
            'pago_em' => null,
        ]);
    }

    private function gerarCodigoPixFake(Pagamento $pagamento): string
    {
        // Apenas para prototipagem. Integração real deve ser fornecida pelo Mercado Pago SDK.
        return sprintf('PIX:%s:%.2f', $pagamento->referencia_gateway, $pagamento->valor_total);
    }
}
