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
        return DB::transaction(function () use ($referenciaGateway, $payload) {
            /** @var Pagamento|null $pagamento */
            $pagamento = Pagamento::query()
                ->where('referencia_gateway', $referenciaGateway)
                ->lockForUpdate()
                ->first();

            if (! $pagamento) {
                return null;
            }

            $statusAtual = strtoupper((string) $pagamento->status);
            $novoStatus = strtoupper((string) Arr::get($payload, 'status', 'PAID'));

            $prioridadeAtual = $this->statusPriority($statusAtual);
            $prioridadeNova = $this->statusPriority($novoStatus);

            if ($prioridadeAtual > 0 && $prioridadeNova > 0 && $prioridadeNova < $prioridadeAtual) {
                return $pagamento->fresh(['agendamento']);
            }

            $valorCapturado = Arr::get(
                $payload,
                'valor_capturado',
                $pagamento->valor_capturado ?? $pagamento->valor_total
            );

            $pagamento->fill([
                'status' => $novoStatus,
                'valor_capturado' => is_numeric($valorCapturado) ? $valorCapturado : $pagamento->valor_capturado,
                'payload' => $payload,
            ]);

            if ($this->statusIndicaPagamentoConfirmado($novoStatus) && ! $pagamento->pago_em) {
                $pagamento->pago_em = Carbon::now();
            }

            $pagamento->save();

            $agendamento = $pagamento->agendamento;

            if ($agendamento && $this->statusIndicaPagamentoConfirmado($novoStatus)) {
                $agendamento->update([
                    'pagamento_confirmado' => true,
                    'status' => $agendamento->status === 'PENDENTE' ? 'CONFIRMADO' : $agendamento->status,
                ]);
            }

            return $pagamento->fresh(['agendamento']);
        });
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
        $percentualConfigurado = Configuracao::get('taxa_cancelamento_percentual', 100);
        $percentual = $percentualOverride ?? (is_numeric($percentualConfigurado) ? (float) $percentualConfigurado : 100);
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

    private function statusIndicaPagamentoConfirmado(string $status): bool
    {
        return in_array($status, ['PAID', 'APPROVED'], true);
    }

    private function statusPriority(string $status): int
    {
        return match ($status) {
            'PENDING' => 10,
            'DUE' => 15,
            'IN_PROCESS', 'AUTHORIZED' => 20,
            'APPROVED' => 30,
            'PAID' => 40,
            'REFUNDED', 'CANCELLED' => 50,
            'CHARGED_BACK' => 60,
            default => 0,
        };
    }
}
