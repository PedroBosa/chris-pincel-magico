<?php

namespace Tests\Feature\Api\V1;

use App\Models\Agendamento;
use App\Models\Disponibilidade;
use App\Models\Servico;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AgendamentoApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_agendamento_record(): void
    {
        $user = User::factory()->create();
        $servico = Servico::factory()->create(['duracao_minutos' => 60]);

        $inicio = now()->addDay()->setTime(10, 0);

        Disponibilidade::factory()->create([
            'dia_semana' => $inicio->dayOfWeek,
            'hora_inicio' => '08:00:00',
            'hora_fim' => '20:00:00',
        ]);

        $expectedFim = $inicio->copy()->addMinutes($servico->duracao_minutos);

        $response = $this->postJson(route('api.v1.agendamentos.store'), [
            'user_id' => $user->id,
            'servico_id' => $servico->id,
            'data_hora_inicio' => $inicio->toDateTimeString(),
            'valor_total' => 150,
            'valor_sinal' => 45,
        ]);

        $response->assertCreated();

        $this->assertDatabaseCount('agendamentos', 1);

        /** @var Agendamento $agendamento */
        $agendamento = Agendamento::first();

        $this->assertEquals($user->id, $agendamento->user_id);
        $this->assertEquals($servico->id, $agendamento->servico_id);
        $this->assertEquals(150.0, (float) $agendamento->valor_total);
    $this->assertEquals($expectedFim->format('Y-m-d H:i:s'), $agendamento->data_hora_fim->format('Y-m-d H:i:s'));
    }
}
