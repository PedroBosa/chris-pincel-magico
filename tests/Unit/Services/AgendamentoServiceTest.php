<?php

namespace Tests\Unit\Services;

use App\Models\Agendamento;
use App\Models\Disponibilidade;
use App\Models\Servico;
use App\Models\User;
use App\Services\AgendamentoService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AgendamentoServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_cria_agendamento_de_retoque_quando_dentro_do_periodo(): void
    {
        Carbon::setTestNow('2025-05-01 09:00:00');

        $service = new AgendamentoService();

        $cliente = User::factory()->create();
        $servico = Servico::factory()->create([
            'preco' => 200,
            'preco_retoque' => 80,
            'dias_para_retoque' => 30,
            'duracao_minutos' => 90,
        ]);

        $inicioSolicitado = Carbon::now()->addDays(2)->setTime(10, 0);

        Disponibilidade::factory()->create([
            'dia_semana' => $inicioSolicitado->dayOfWeek,
            'hora_inicio' => '08:00:00',
            'hora_fim' => '20:00:00',
        ]);

        $ultimoAgendamento = Agendamento::factory()->create([
            'user_id' => $cliente->id,
            'servico_id' => $servico->id,
            'status' => 'CONCLUIDO',
            'data_hora_inicio' => Carbon::now()->subDays(10)->setTime(9, 0),
            'data_hora_fim' => Carbon::now()->subDays(10)->setTime(10, 30),
            'valor_total' => $servico->preco,
        ]);

        $agendamento = $service->criarAgendamento([
            'user_id' => $cliente->id,
            'servico_id' => $servico->id,
            'data_hora_inicio' => $inicioSolicitado->toDateTimeString(),
        ]);

        $this->assertEquals('RETOQUE', $agendamento->tipo);
        $this->assertEquals($servico->preco_retoque, (float) $agendamento->valor_total);
        $this->assertEquals($ultimoAgendamento->id, $agendamento->agendamento_original_id);
        $this->assertEquals(24.0, (float) $agendamento->valor_sinal); // 30% do valor de retoque
    }

    public function test_calcula_taxa_cancelamento_com_configuracao_padrao(): void
    {
        Carbon::setTestNow('2025-05-01 09:00:00');

        $service = new AgendamentoService();

        $agendamento = Agendamento::factory()->create([
            'data_hora_inicio' => Carbon::now()->addHours(6),
            'valor_sinal' => 100,
        ]);

        $taxa = $service->calcularTaxaCancelamento($agendamento);

        $this->assertEquals(100.0, $taxa);
    }
}
