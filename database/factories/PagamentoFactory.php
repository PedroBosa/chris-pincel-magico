<?php

namespace Database\Factories;

use App\Models\Agendamento;
use App\Models\Pagamento;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Pagamento>
 */
class PagamentoFactory extends Factory
{
    protected $model = Pagamento::class;

    public function definition(): array
    {
        return [
            'agendamento_id' => Agendamento::factory(),
            'gateway' => 'mercado_pago',
            'referencia_gateway' => Str::uuid()->toString(),
            'status' => 'pending',
            'valor_total' => $this->faker->randomFloat(2, 80, 500),
            'valor_capturado' => null,
            'forma_pagamento' => 'pix',
            'payload' => [],
            'pago_em' => null,
        ];
    }
}
