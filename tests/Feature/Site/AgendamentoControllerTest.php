<?php

namespace Tests\Feature\Site;

use App\Models\Servico;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AgendamentoControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_displays_agendamento_form(): void
    {
        $servico = Servico::factory()->create();

        $response = $this->get(route('agendamentos.novo'));

        $response->assertOk();
        $response->assertSee($servico->nome);
    }

    public function test_it_processes_agendamento_request_and_redirects_to_confirmation(): void
    {
        $servico = Servico::factory()->create();

        $payload = [
            'servico_id' => $servico->id,
            'data' => now()->addDay()->format('Y-m-d'),
            'hora' => '10:00',
        ];

        $response = $this->post(route('agendamentos.store'), $payload);

        $response->assertRedirect(route('agendamentos.confirmacao'));
        $response->assertSessionHas('agendamento', $payload);
    }

    public function test_authenticated_user_can_view_account_page(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->get(route('conta.index'));

        $response->assertOk();
        $response->assertSee($user->name);
    }
}
