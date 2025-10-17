<?php

namespace Database\Seeders;

use App\Models\Agendamento;
use App\Models\Pagamento;
use Illuminate\Database\Seeder;

class PagamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pegar agendamentos existentes (todos exceto cancelados)
        $agendamentos = Agendamento::where('status', '!=', 'CANCELADO')
            ->get();

        if ($agendamentos->isEmpty()) {
            $this->command->warn('⚠️  Nenhum agendamento encontrado. Execute AgendamentoTesteSeeder primeiro.');
            return;
        }

        $formasPagamento = ['credit_card', 'debit_card', 'pix'];
        $statusList = ['approved', 'pending', 'cancelled'];
        $count = 0;

        foreach ($agendamentos as $agendamento) {
            // Verificar se já tem pagamento
            if (Pagamento::where('agendamento_id', $agendamento->id)->exists()) {
                continue;
            }

            // 90% de chance de ter pagamento
            if (rand(1, 100) <= 90) {
                // 70% aprovado, 20% pendente, 10% cancelado
                $rand = rand(1, 100);
                if ($rand <= 70) {
                    $status = 'approved';
                } elseif ($rand <= 90) {
                    $status = 'pending';
                } else {
                    $status = 'cancelled';
                }
                
                $formaPagamento = $formasPagamento[array_rand($formasPagamento)];
                
                // Valores
                $valorTotal = $agendamento->valor_total ?? rand(50, 500);
                $valorCapturado = $status === 'approved' ? $valorTotal : null;

                // Criar data aleatória nos últimos 60 dias
                $createdAt = now()->subDays(rand(0, 60))->setHour(rand(8, 20))->setMinute(rand(0, 59));

                Pagamento::create([
                    'agendamento_id' => $agendamento->id,
                    'gateway' => 'mercado_pago',
                    'referencia_gateway' => 'MP' . time() . rand(1000, 9999),
                    'status' => $status,
                    'valor_total' => $valorTotal,
                    'valor_capturado' => $valorCapturado,
                    'forma_pagamento' => $formaPagamento,
                    'payload' => json_encode([
                        'transaction_id' => 'TXN' . rand(100000, 999999),
                        'authorization_code' => rand(100000, 999999),
                    ]),
                    'pago_em' => $status === 'approved' ? $createdAt : null,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);

                $count++;
            }
        }

        $this->command->info("✅ {$count} pagamentos criados com sucesso! 💰");
    }
}
