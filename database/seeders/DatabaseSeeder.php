<?php

namespace Database\Seeders;

use App\Models\Configuracao;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::query()->updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_admin' => true,
            ]
        );

        $defaults = [
            'tolerancia_atraso_minutos' => [
                'valor' => 15,
                'descricao' => 'Minutos de tolerância antes de marcar como atraso.',
            ],
            'horas_minimas_cancelamento' => [
                'valor' => 24,
                'descricao' => 'Horas mínimas para cancelamento sem multa.',
            ],
            'taxa_cancelamento_percentual' => [
                'valor' => 100,
                'descricao' => 'Percentual aplicado ao sinal em cancelamentos tardios.',
            ],
            'whatsapp_numero' => [
                'valor' => '+5586999999999',
                'descricao' => 'Número do remetente WhatsApp.',
            ],
            'horario_lembrete_horas' => [
                'valor' => 24,
                'descricao' => 'Antecedência em horas para envio de lembretes.',
            ],
            'pontos_por_real_gasto' => [
                'valor' => 1,
                'descricao' => 'Taxa de conversão de reais para pontos.',
            ],
            'pontos_para_desconto' => [
                'valor' => 100,
                'descricao' => 'Quantidade de pontos necessária para resgate.',
            ],
            'valor_desconto_pontos' => [
                'valor' => 10.0,
                'descricao' => 'Valor em reais concedido ao resgatar pontos.',
            ],
            'dias_expiracao_pontos' => [
                'valor' => 365,
                'descricao' => 'Dias até expirar pontos não utilizados.',
            ],
            'sinal_percentual_padrao' => [
                'valor' => 30,
                'descricao' => 'Percentual do valor cobrado como sinal.',
            ],
        ];

        foreach ($defaults as $chave => $dados) {
            Configuracao::query()->updateOrCreate(
                ['chave' => $chave],
                [
                    'valor' => ['valor' => $dados['valor']],
                    'descricao' => $dados['descricao'],
                ]
            );
        }
    }
}
