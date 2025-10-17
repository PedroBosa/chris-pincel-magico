<?php

namespace Database\Factories;

use App\Models\Agendamento;
use App\Models\FotoCliente;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FotoCliente>
 */
class FotoClienteFactory extends Factory
{
    protected $model = FotoCliente::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'agendamento_id' => Agendamento::factory(),
            'caminho' => 'portfolio/' . $this->faker->uuid() . '.jpg',
            'legenda' => $this->faker->optional()->sentence(),
            'publicado' => $this->faker->boolean(),
            'ordem' => $this->faker->numberBetween(0, 10),
        ];
    }
}
