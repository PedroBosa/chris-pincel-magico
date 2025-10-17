<?php

namespace Database\Factories;

use App\Models\Servico;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Servico>
 */
class ServicoFactory extends Factory
{
    protected $model = Servico::class;

    public function definition(): array
    {
        $nome = $this->faker->unique()->words(3, true);

        return [
            'nome' => Str::title($nome),
            'slug' => Str::slug($nome),
            'descricao' => $this->faker->sentence(),
            'duracao_minutos' => $this->faker->numberBetween(30, 180),
            'preco' => $this->faker->randomFloat(2, 80, 500),
            'preco_retoque' => $this->faker->randomFloat(2, 40, 250),
            'dias_para_retoque' => 30,
            'ativo' => true,
        ];
    }
}
