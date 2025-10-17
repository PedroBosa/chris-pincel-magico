<?php

namespace Database\Factories;

use App\Models\Promocao;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<Promocao>
 */
class PromocaoFactory extends Factory
{
    protected $model = Promocao::class;

    public function definition(): array
    {
        $titulo = $this->faker->unique()->sentence(3);
        $inicio = Carbon::now()->addDays($this->faker->numberBetween(0, 5));

        return [
            'titulo' => $titulo,
            'slug' => Str::slug($titulo),
            'descricao' => $this->faker->sentence(8),
            'tipo' => 'PERCENTUAL',
            'valor_desconto' => null,
            'percentual_desconto' => $this->faker->numberBetween(10, 50),
            'codigo_cupom' => strtoupper(Str::random(8)),
            'inicio_vigencia' => $inicio,
            'fim_vigencia' => (clone $inicio)->addWeeks(4),
            'limite_uso' => 100,
            'usos_realizados' => 0,
            'ativo' => true,
            'restricoes' => [],
        ];
    }
}
