<?php

namespace Database\Factories;

use App\Models\Disponibilidade;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Disponibilidade>
 */
class DisponibilidadeFactory extends Factory
{
    protected $model = Disponibilidade::class;

    public function definition(): array
    {
        $horaInicio = $this->faker->numberBetween(8, 14);
        $duracao = $this->faker->numberBetween(2, 6);

        return [
            'dia_semana' => $this->faker->numberBetween(0, 6),
            'hora_inicio' => sprintf('%02d:00:00', $horaInicio),
            'hora_fim' => sprintf('%02d:00:00', $horaInicio + $duracao),
            'ativo' => true,
        ];
    }
}
