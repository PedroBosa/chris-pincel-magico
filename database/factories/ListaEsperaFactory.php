<?php

namespace Database\Factories;

use App\Models\ListaEspera;
use App\Models\Servico;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<ListaEspera>
 */
class ListaEsperaFactory extends Factory
{
    protected $model = ListaEspera::class;

    public function definition(): array
    {
        return [
            'servico_id' => Servico::factory(),
            'user_id' => User::factory(),
            'nome' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'telefone' => $this->faker->phoneNumber(),
            'data_hora_desejada' => Carbon::now()->addDays(3),
            'status' => 'AGUARDANDO',
            'observacoes' => $this->faker->optional()->sentence(),
        ];
    }
}
