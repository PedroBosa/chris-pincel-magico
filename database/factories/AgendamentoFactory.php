<?php

namespace Database\Factories;

use App\Models\Agendamento;
use App\Models\Servico;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<Agendamento>
 */
class AgendamentoFactory extends Factory
{
    protected $model = Agendamento::class;

    public function definition(): array
    {
        $inicio = Carbon::now()->addDays($this->faker->numberBetween(1, 30))->setTime(rand(9, 18), 0);
        $duracao = $this->faker->numberBetween(60, 180);
        $fim = (clone $inicio)->addMinutes($duracao);

        return [
            'user_id' => User::factory(),
            'servico_id' => Servico::factory(),
            'status' => 'PENDENTE',
            'tipo' => 'NORMAL',
            'data_hora_inicio' => $inicio,
            'data_hora_fim' => $fim,
            'valor_total' => $this->faker->randomFloat(2, 80, 500),
            'valor_sinal' => $this->faker->randomFloat(2, 20, 150),
            'taxa_cancelamento' => 0,
            'pagamento_confirmado' => false,
            'lembrete_enviado' => false,
            'canal_origem' => 'SITE',
            'metadados' => [],
        ];
    }
}
