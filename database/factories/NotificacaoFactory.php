<?php

namespace Database\Factories;

use App\Models\Agendamento;
use App\Models\Notificacao;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<Notificacao>
 */
class NotificacaoFactory extends Factory
{
    protected $model = Notificacao::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'agendamento_id' => Agendamento::factory(),
            'canal' => $this->faker->randomElement(['whatsapp', 'email']),
            'assunto' => $this->faker->sentence(4),
            'mensagem' => $this->faker->paragraph(),
            'status' => 'ENFILEIRADO',
            'enviado_em' => Carbon::now(),
            'payload' => [],
        ];
    }
}
