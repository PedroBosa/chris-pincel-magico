<?php

namespace Database\Factories;

use App\Models\PontosFidelidade;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<PontosFidelidade>
 */
class PontosFidelidadeFactory extends Factory
{
    protected $model = PontosFidelidade::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'pontos_atuais' => $this->faker->numberBetween(0, 500),
            'pontos_acumulados' => $this->faker->numberBetween(0, 1000),
            'ultima_atualizacao' => Carbon::now(),
        ];
    }
}
