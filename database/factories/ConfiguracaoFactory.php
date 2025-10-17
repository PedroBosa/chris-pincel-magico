<?php

namespace Database\Factories;

use App\Models\Configuracao;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Configuracao>
 */
class ConfiguracaoFactory extends Factory
{
    protected $model = Configuracao::class;

    public function definition(): array
    {
        $chave = 'config_' . Str::slug($this->faker->unique()->word());

        return [
            'chave' => $chave,
            'valor' => ['value' => $this->faker->word()],
            'descricao' => $this->faker->sentence(),
        ];
    }
}
