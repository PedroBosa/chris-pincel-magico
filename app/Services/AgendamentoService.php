<?php

namespace App\Services;

use App\Models\Agendamento;
use App\Models\BloqueioHorario;
use App\Models\Configuracao;
use App\Models\Disponibilidade;
use App\Models\Servico;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use InvalidArgumentException;

class AgendamentoService
{
    /**
     * Cria um agendamento considerando regras de retoque, disponibilidade e valores padrão.
     */
    public function criarAgendamento(array $dados): Agendamento
    {
        $userId = Arr::get($dados, 'user_id');
        $servicoId = Arr::get($dados, 'servico_id');
        $dataHoraInicioRaw = Arr::get($dados, 'data_hora_inicio');

        if (! $userId || ! $servicoId || ! $dataHoraInicioRaw) {
            throw new InvalidArgumentException('Os campos user_id, servico_id e data_hora_inicio são obrigatórios.');
        }

        $servico = Servico::findOrFail($servicoId);
        $dataHoraInicio = Carbon::parse($dataHoraInicioRaw);

        $verificacao = $this->verificarDisponibilidade(clone $dataHoraInicio, $servico->id);

        if (! $verificacao['disponivel']) {
            $motivo = $verificacao['motivo'] ?? 'Horário indisponível para agendamento.';
            throw new InvalidArgumentException($motivo);
        }

        $tipoInformado = strtoupper((string) Arr::get($dados, 'tipo', ''));
        $tipo = $tipoInformado ?: 'NORMAL';
        $agendamentoOriginalId = Arr::get($dados, 'agendamento_original_id');

        if ($tipo === 'NORMAL' && ! $agendamentoOriginalId) {
            $agendamentoElegivel = $this->buscarAgendamentoElegivelParaRetoque($userId, $servico);

            if ($agendamentoElegivel) {
                $tipo = 'RETOQUE';
                $agendamentoOriginalId = $agendamentoElegivel->getKey();
            }
        }

        $valorBaseServico = (float) ($servico->preco ?? 0);
        $valorRetoque = (float) ($servico->preco_retoque ?? 0);

        if ($tipo === 'RETOQUE' && $valorRetoque > 0) {
            $valorTotal = $valorRetoque;
            $valorOriginal = $valorBaseServico > 0 ? $valorBaseServico : null;
            $valorDesconto = $valorOriginal ? max(0, $valorOriginal - $valorTotal) : 0;
        } else {
            $valorTotal = (float) ($dados['valor_total'] ?? $valorBaseServico);
            $valorOriginal = Arr::get($dados, 'valor_original');
            $valorDesconto = (float) ($dados['valor_desconto'] ?? 0);
        }

        $valorTotal = round(max(0, $valorTotal), 2);
        $valorSinal = array_key_exists('valor_sinal', $dados)
            ? round(max(0, (float) $dados['valor_sinal']), 2)
            : round($valorTotal * 0.30, 2);

        $payload = array_merge([
            'user_id' => $userId,
            'servico_id' => $servico->id,
            'agendamento_original_id' => $agendamentoOriginalId,
            'status' => Arr::get($dados, 'status', 'PENDENTE'),
            'tipo' => $tipo,
            'data_hora_inicio' => $verificacao['data_hora_inicio'],
            'data_hora_fim' => $verificacao['data_hora_fim'],
            'valor_total' => $valorTotal,
            'valor_sinal' => $valorSinal,
            'valor_desconto' => $valorDesconto,
            'valor_original' => $valorOriginal,
            'forma_pagamento' => Arr::get($dados, 'forma_pagamento'),
            'forma_pagamento_sinal' => Arr::get($dados, 'forma_pagamento_sinal', Arr::get($dados, 'forma_pagamento')),
            'observacoes' => Arr::get($dados, 'observacoes'),
            'metadados' => Arr::get($dados, 'metadados'),
            'canal_origem' => Arr::get($dados, 'canal_origem', 'SITE'),
        ], Arr::only($dados, [
            'promocao_id',
            'codigo_cupom_usado',
        ]));

        return Agendamento::create($payload);
    }

    /**
     * Busca um agendamento concluído elegível para ser usado como referência de retoque.
     */
    protected function buscarAgendamentoElegivelParaRetoque(int $userId, Servico $servico): ?Agendamento
    {
        $diasLimite = (int) ($servico->dias_para_retoque ?? 0);
        $precoRetoque = (float) ($servico->preco_retoque ?? 0);

        if ($diasLimite <= 0 || $precoRetoque <= 0) {
            return null;
        }

        $limite = Carbon::now()->subDays($diasLimite);

        return Agendamento::query()
            ->where('user_id', $userId)
            ->where('servico_id', $servico->id)
            ->where('status', 'CONCLUIDO')
            ->where('data_hora_inicio', '>=', $limite)
            ->orderByDesc('data_hora_inicio')
            ->first();
    }

    /**
     * Calcula a taxa de cancelamento aplicável para um agendamento.
     */
    public function calcularTaxaCancelamento(Agendamento $agendamento): float
    {
        $percentualConfigurado = Configuracao::get('taxa_cancelamento_percentual', 100);
        $percentual = is_numeric($percentualConfigurado)
            ? (float) $percentualConfigurado
            : 100.0;

        $valorSinal = (float) ($agendamento->valor_sinal ?? 0);

        if ($valorSinal <= 0 || $percentual <= 0) {
            return 0.0;
        }

        return round($valorSinal * ($percentual / 100), 2);
    }

    /**
     * Verifica se um horário está disponível para agendamento
     */
    public function verificarDisponibilidade(
        Carbon $dataHoraInicio,
        int $servicoId,
        ?int $agendamentoId = null
    ): array {
        $servico = Servico::findOrFail($servicoId);
        $duracaoMinutos = $servico->duracao_minutos ?? 60;
        
        $dataHoraFim = (clone $dataHoraInicio)->addMinutes($duracaoMinutos);

        // 1. Verificar se a data não é no passado
        if ($dataHoraInicio->isPast()) {
            return [
                'disponivel' => false,
                'motivo' => 'Não é possível agendar para datas passadas.',
                'tipo' => 'data_passada'
            ];
        }

        // 2. Verificar se está dentro do horário de funcionamento
        $disponibilidadeCheck = $this->verificarHorarioFuncionamento($dataHoraInicio, $dataHoraFim);
        if (!$disponibilidadeCheck['disponivel']) {
            return $disponibilidadeCheck;
        }

        // 3. Verificar bloqueios de horário
        $bloqueioCheck = $this->verificarBloqueios($dataHoraInicio, $dataHoraFim);
        if (!$bloqueioCheck['disponivel']) {
            return $bloqueioCheck;
        }

        // 4. Verificar conflitos com outros agendamentos
        $conflitoCheck = $this->verificarConflitos($dataHoraInicio, $dataHoraFim, $agendamentoId);
        if (!$conflitoCheck['disponivel']) {
            return $conflitoCheck;
        }

        // 5. Verificar antecedência mínima (pelo menos 2 horas de antecedência)
        $horasAntecedencia = now()->diffInHours($dataHoraInicio, false);
        if ($horasAntecedencia < 2) {
            return [
                'disponivel' => false,
                'motivo' => 'É necessário agendar com pelo menos 2 horas de antecedência.',
                'tipo' => 'antecedencia_minima'
            ];
        }

        // 6. Verificar antecedência máxima (configurável)
        $diasAntecedenciaMaxima = config_site('dias_antecedencia_agendamento', 30);
        $diasAntecedencia = now()->diffInDays($dataHoraInicio, false);
        if ($diasAntecedencia > $diasAntecedenciaMaxima) {
            return [
                'disponivel' => false,
                'motivo' => "Só é possível agendar com até {$diasAntecedenciaMaxima} dias de antecedência.",
                'tipo' => 'antecedencia_maxima'
            ];
        }

        return [
            'disponivel' => true,
            'data_hora_inicio' => $dataHoraInicio,
            'data_hora_fim' => $dataHoraFim,
            'duracao_minutos' => $duracaoMinutos
        ];
    }

    /**
     * Verifica se o horário está dentro do expediente
     */
    protected function verificarHorarioFuncionamento(Carbon $inicio, Carbon $fim): array
    {
        $diaSemana = $inicio->dayOfWeek; // 0 = Domingo, 6 = Sábado
        
        $disponibilidade = Disponibilidade::where('dia_semana', $diaSemana)
            ->where('ativo', true)
            ->first();

        if (!$disponibilidade) {
            return [
                'disponivel' => false,
                'motivo' => 'Não há atendimento neste dia da semana.',
                'tipo' => 'dia_fechado'
            ];
        }

        // Converte strings de hora para comparação
        $horaInicio = $inicio->format('H:i');
        $horaFim = $fim->format('H:i');
        
        // Garante que hora_inicio e hora_fim estão no formato H:i
        $horaAbertura = is_string($disponibilidade->hora_inicio) 
            ? substr($disponibilidade->hora_inicio, 0, 5) 
            : \Carbon\Carbon::parse($disponibilidade->hora_inicio)->format('H:i');
            
        $horaFechamento = is_string($disponibilidade->hora_fim) 
            ? substr($disponibilidade->hora_fim, 0, 5) 
            : \Carbon\Carbon::parse($disponibilidade->hora_fim)->format('H:i');

        if ($horaInicio < $horaAbertura || $horaFim > $horaFechamento) {
            return [
                'disponivel' => false,
                'motivo' => "Horário de funcionamento: {$horaAbertura} às {$horaFechamento}",
                'tipo' => 'fora_horario',
                'horario_funcionamento' => [
                    'abertura' => $horaAbertura,
                    'fechamento' => $horaFechamento
                ]
            ];
        }

        return ['disponivel' => true];
    }

    /**
     * Verifica se há bloqueios no período
     */
    protected function verificarBloqueios(Carbon $inicio, Carbon $fim): array
    {
        // Verifica bloqueios pontuais
        $bloqueio = BloqueioHorario::where('recorrente', false)
            ->where(function ($query) use ($inicio, $fim) {
                $query->whereBetween('inicio', [$inicio, $fim])
                    ->orWhereBetween('fim', [$inicio, $fim])
                    ->orWhere(function ($q) use ($inicio, $fim) {
                        $q->where('inicio', '<=', $inicio)
                          ->where('fim', '>=', $fim);
                    });
            })
            ->first();

        if ($bloqueio) {
            return [
                'disponivel' => false,
                'motivo' => $bloqueio->motivo ?? 'Horário bloqueado para agendamentos.',
                'tipo' => 'bloqueio',
                'bloqueio' => [
                    'inicio' => $bloqueio->inicio,
                    'fim' => $bloqueio->fim
                ]
            ];
        }

        // Verifica bloqueios recorrentes
        $diaSemana = $inicio->dayOfWeek;
        $bloqueioRecorrente = BloqueioHorario::where('recorrente', true)
            ->where('dia_semana', $diaSemana)
            ->first();

        if ($bloqueioRecorrente) {
            return [
                'disponivel' => false,
                'motivo' => $bloqueioRecorrente->motivo ?? 'Este dia da semana está bloqueado.',
                'tipo' => 'bloqueio_recorrente'
            ];
        }

        return ['disponivel' => true];
    }

    /**
     * Verifica conflitos com outros agendamentos
     */
    protected function verificarConflitos(Carbon $inicio, Carbon $fim, ?int $agendamentoId = null): array
    {
        $query = Agendamento::where(function ($query) use ($inicio, $fim) {
            $query->whereBetween('data_hora_inicio', [$inicio, $fim])
                ->orWhereBetween('data_hora_fim', [$inicio, $fim])
                ->orWhere(function ($q) use ($inicio, $fim) {
                    $q->where('data_hora_inicio', '<=', $inicio)
                      ->where('data_hora_fim', '>=', $fim);
                });
        })
        ->whereIn('status', ['PENDENTE', 'CONFIRMADO']);

        // Ignora o próprio agendamento em caso de edição
        if ($agendamentoId) {
            $query->where('id', '!=', $agendamentoId);
        }

        $conflito = $query->first();

        if ($conflito) {
            return [
                'disponivel' => false,
                'motivo' => 'Já existe um agendamento neste horário.',
                'tipo' => 'conflito',
                'agendamento_conflitante' => [
                    'id' => $conflito->id,
                    'inicio' => $conflito->data_hora_inicio,
                    'fim' => $conflito->data_hora_fim
                ]
            ];
        }

        return ['disponivel' => true];
    }

    /**
     * Retorna horários disponíveis para um dia específico
     */
    public function horariosDisponiveis(Carbon $data, int $servicoId): array
    {
        $servico = Servico::findOrFail($servicoId);
        $duracaoMinutos = $servico->duracao_minutos ?? 60;
        
        $diaSemana = $data->dayOfWeek;
        $disponibilidade = Disponibilidade::where('dia_semana', $diaSemana)
            ->where('ativo', true)
            ->first();

        if (!$disponibilidade) {
            return [];
        }

        $horariosDisponiveis = [];
        $horaAtual = Carbon::parse($data->format('Y-m-d') . ' ' . $disponibilidade->hora_inicio);
        $horaFim = Carbon::parse($data->format('Y-m-d') . ' ' . $disponibilidade->hora_fim);

        // Intervalo de 30 minutos entre horários
        $intervalo = 30;

        while ($horaAtual->addMinutes($duracaoMinutos)->lessThanOrEqualTo($horaFim)) {
            $horaAtual->subMinutes($duracaoMinutos);
            
            $verificacao = $this->verificarDisponibilidade(
                clone $horaAtual,
                $servicoId
            );

            if ($verificacao['disponivel']) {
                $horariosDisponiveis[] = [
                    'horario' => $horaAtual->format('H:i'),
                    'datetime' => $horaAtual->toDateTimeString(),
                    'disponivel' => true
                ];
            }

            $horaAtual->addMinutes($intervalo);
        }

        return $horariosDisponiveis;
    }
}
