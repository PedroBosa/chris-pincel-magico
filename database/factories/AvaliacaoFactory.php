<?php

namespace Database\Factories;

use App\Models\Agendamento;
use App\Models\Avaliacao;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<Avaliacao>
 */
class AvaliacaoFactory extends Factory
{
    protected $model = Avaliacao::class;

    public function definition(): array
    {
        return [
            'agendamento_id' => Agendamento::factory(),
            'user_id' => User::factory(),
            'nota' => $this->faker->numberBetween(1, 5),
            'comentario' => $this->faker->optional()->paragraph(),
            'publicado' => true,
            'publicado_em' => Carbon::now(),
        ];
    }
}
