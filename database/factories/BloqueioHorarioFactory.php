<?php

namespace Database\Factories;

use App\Models\BloqueioHorario;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<BloqueioHorario>
 */
class BloqueioHorarioFactory extends Factory
{
    protected $model = BloqueioHorario::class;

    public function definition(): array
    {
        $inicio = Carbon::now()->addWeeks($this->faker->numberBetween(1, 8))->startOfDay();

        return [
            'inicio' => $inicio,
            'fim' => (clone $inicio)->addHours(8),
            'motivo' => $this->faker->sentence(3),
            'recorrente' => false,
            'dia_semana' => null,
        ];
    }
}
