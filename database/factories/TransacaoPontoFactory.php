<?php

namespace Database\Factories;

use App\Models\Agendamento;
use App\Models\PontosFidelidade;
use App\Models\TransacaoPonto;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<TransacaoPonto>
 */
class TransacaoPontoFactory extends Factory
{
    protected $model = TransacaoPonto::class;

    public function definition(): array
    {
        return [
            'pontos_fidelidade_id' => PontosFidelidade::factory(),
            'agendamento_id' => Agendamento::factory(),
            'tipo' => $this->faker->randomElement(['CREDITO', 'DEBITO']),
            'pontos' => $this->faker->numberBetween(10, 100),
            'valor_referencia' => $this->faker->randomFloat(2, 50, 500),
            'descricao' => $this->faker->sentence(),
            'metadados' => [],
            'registrado_em' => Carbon::now(),
        ];
    }
}
