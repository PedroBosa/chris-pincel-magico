<?php

namespace App\Services;

use App\Models\Agendamento;
use App\Models\PontosFidelidade;
use App\Models\TransacaoPonto;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use function collect;

class ProgramaFidelidadeService
{
    public function obterConfiguracao(): array
    {
        return [
            'pontos_por_real' => (int) config('fidelidade.pontos_por_real', 1),
            'valor_por_ponto' => (float) config('fidelidade.valor_por_ponto', 0),
            'resgate_minimo' => (int) config('fidelidade.resgate_minimo', 0),
            'maximo_percentual_resgate' => (float) config('fidelidade.maximo_percentual_resgate', 0),
            'bonus_primeira_compra' => (int) config('fidelidade.bonus_primeira_compra', 0),
            'niveis' => config('fidelidade.niveis', []),
        ];
    }

    public function obterOuCriarCarteira(User $user, bool $lock = false): PontosFidelidade
    {
        $query = PontosFidelidade::query()->where('user_id', $user->id);

        if ($lock) {
            $query->lockForUpdate();
        }

        $carteira = $query->first();

        if (! $carteira) {
            $carteira = PontosFidelidade::create([
                'user_id' => $user->id,
                'pontos_atuais' => 0,
                'pontos_acumulados' => 0,
                'ultima_atualizacao' => now(),
            ]);
        }

        return $carteira;
    }

    public function calcularPontosPorValor(float $valor): int
    {
        $config = $this->obterConfiguracao();
        $pontosPorReal = max(0, $config['pontos_por_real']);

        if ($pontosPorReal <= 0 || $valor <= 0) {
            return 0;
        }

        return max(0, (int) floor($valor * $pontosPorReal));
    }

    public function calcularValorPorPontos(int $pontos): float
    {
        $config = $this->obterConfiguracao();
        $valorPorPonto = max(0, $config['valor_por_ponto']);

        if ($valorPorPonto <= 0 || $pontos <= 0) {
            return 0.0;
        }

        return round($pontos * $valorPorPonto, 2);
    }

    public function calcularMaximoPontosResgate(float $valorBase, int $pontosDisponiveis): int
    {
        $config = $this->obterConfiguracao();
        $valorPorPonto = max(0, $config['valor_por_ponto']);
        $percentualMaximo = max(0, min(100, $config['maximo_percentual_resgate']));

        if ($valorBase <= 0 || $pontosDisponiveis <= 0 || $valorPorPonto <= 0 || $percentualMaximo <= 0) {
            return 0;
        }

        $valorMaximoDesconto = $valorBase * ($percentualMaximo / 100);

        if ($valorMaximoDesconto <= 0) {
            return 0;
        }

        $maximoPorValor = (int) floor($valorMaximoDesconto / $valorPorPonto);

        if ($maximoPorValor <= 0) {
            return 0;
        }

        return max(0, min($pontosDisponiveis, $maximoPorValor));
    }

    public function validarResgate(?PontosFidelidade $carteira, ?int $pontosSolicitados, float $valorBase): array
    {
        $config = $this->obterConfiguracao();
        $pontosSolicitados = (int) $pontosSolicitados;

        if (! $carteira || $carteira->pontos_atuais <= 0 || $pontosSolicitados <= 0) {
            return [
                'valido' => false,
                'pontos' => 0,
                'valor_desconto' => 0.0,
                'mensagem' => $pontosSolicitados > 0
                    ? 'Você ainda não possui pontos suficientes para resgatar.'
                    : null,
                'maximo_permitido' => 0,
            ];
        }

        if ($pontosSolicitados < $config['resgate_minimo']) {
            return [
                'valido' => false,
                'pontos' => 0,
                'valor_desconto' => 0.0,
                'mensagem' => sprintf(
                    'O resgate mínimo é de %d pontos.',
                    $config['resgate_minimo']
                ),
                'maximo_permitido' => 0,
            ];
        }

        if ($pontosSolicitados > $carteira->pontos_atuais) {
            return [
                'valido' => false,
                'pontos' => 0,
                'valor_desconto' => 0.0,
                'mensagem' => 'Você não possui pontos suficientes para esse resgate.',
                'maximo_permitido' => $carteira->pontos_atuais,
            ];
        }

        $maximoPermitido = $this->calcularMaximoPontosResgate($valorBase, $carteira->pontos_atuais);

        if ($maximoPermitido < $config['resgate_minimo']) {
            return [
                'valido' => false,
                'pontos' => 0,
                'valor_desconto' => 0.0,
                'mensagem' => 'Este serviço não permite uso de pontos no momento.',
                'maximo_permitido' => $maximoPermitido,
            ];
        }

        if ($pontosSolicitados > $maximoPermitido) {
            return [
                'valido' => false,
                'pontos' => 0,
                'valor_desconto' => 0.0,
                'mensagem' => sprintf(
                    'Você pode usar no máximo %d pontos neste agendamento.',
                    $maximoPermitido
                ),
                'maximo_permitido' => $maximoPermitido,
            ];
        }

        $valorDesconto = $this->calcularValorPorPontos($pontosSolicitados);

        return [
            'valido' => true,
            'pontos' => $pontosSolicitados,
            'valor_desconto' => min($valorBase, $valorDesconto),
            'mensagem' => null,
            'maximo_permitido' => $maximoPermitido,
        ];
    }

    public function registrarDebitoResgate(Agendamento $agendamento, PontosFidelidade $carteira, int $pontos, float $valorDesconto): ?TransacaoPonto
    {
        if ($pontos <= 0) {
            return null;
        }

        if ($this->buscarTransacaoDebito($agendamento)) {
            return null;
        }

        return DB::transaction(function () use ($agendamento, $carteira, $pontos, $valorDesconto) {
            $carteiraBloqueada = PontosFidelidade::query()
                ->whereKey($carteira->id)
                ->lockForUpdate()
                ->first();

            if (! $carteiraBloqueada) {
                return null;
            }

            $pontosAplicados = min($pontos, $carteiraBloqueada->pontos_atuais);

            if ($pontosAplicados <= 0) {
                return null;
            }

            $carteiraBloqueada->pontos_atuais = max(0, $carteiraBloqueada->pontos_atuais - $pontosAplicados);
            $carteiraBloqueada->ultima_atualizacao = now();
            $carteiraBloqueada->save();

            return $carteiraBloqueada->transacoes()->create([
                'agendamento_id' => $agendamento->id,
                'tipo' => 'DEBITO',
                'pontos' => $pontosAplicados,
                'valor_referencia' => $valorDesconto,
                'descricao' => 'Resgate de pontos para desconto no agendamento',
                'metadados' => [
                    'origem' => 'resgate',
                    'status_agendamento' => $agendamento->status,
                ],
                'registrado_em' => now(),
            ]);
        });
    }

    public function estornarDebitoResgate(Agendamento $agendamento): void
    {
        $transacao = $this->buscarTransacaoDebito($agendamento);

        if (! $transacao) {
            return;
        }

        DB::transaction(function () use ($transacao) {
            $carteira = PontosFidelidade::query()
                ->whereKey($transacao->pontos_fidelidade_id)
                ->lockForUpdate()
                ->first();

            if ($carteira) {
                $carteira->pontos_atuais += $transacao->pontos;
                $carteira->ultima_atualizacao = now();
                $carteira->save();
            }

            $transacao->delete();
        });
    }

    public function registrarCreditoConclusao(Agendamento $agendamento): ?TransacaoPonto
    {
        if ($this->buscarTransacaoCredito($agendamento) || $agendamento->valor_total <= 0) {
            return null;
        }

        if (! $agendamento->relationLoaded('cliente')) {
            $agendamento->load('cliente');
        }

        $cliente = $agendamento->cliente;

        if (! $cliente) {
            return null;
        }

        $pontos = $this->calcularPontosPorValor((float) $agendamento->valor_total);

        if ($pontos <= 0) {
            return null;
        }

        return DB::transaction(function () use ($agendamento, $cliente, $pontos) {
            $carteira = $this->obterOuCriarCarteira($cliente, lock: true);

            $carteira->pontos_atuais += $pontos;
            $carteira->pontos_acumulados += $pontos;
            $carteira->ultima_atualizacao = now();
            $carteira->save();

            return $carteira->transacoes()->create([
                'agendamento_id' => $agendamento->id,
                'tipo' => 'CREDITO',
                'pontos' => $pontos,
                'valor_referencia' => $agendamento->valor_total,
                'descricao' => 'Pontos acumulados por agendamento concluído',
                'metadados' => [
                    'origem' => 'agendamento_concluido',
                    'status_agendamento' => $agendamento->status,
                ],
                'registrado_em' => now(),
            ]);
        });
    }

    public function estornarCreditoConclusao(Agendamento $agendamento): void
    {
        $transacao = $this->buscarTransacaoCredito($agendamento);

        if (! $transacao) {
            return;
        }

        DB::transaction(function () use ($transacao) {
            $carteira = PontosFidelidade::query()
                ->whereKey($transacao->pontos_fidelidade_id)
                ->lockForUpdate()
                ->first();

            if ($carteira) {
                $carteira->pontos_atuais = max(0, $carteira->pontos_atuais - $transacao->pontos);
                $carteira->pontos_acumulados = max(0, $carteira->pontos_acumulados - $transacao->pontos);
                $carteira->ultima_atualizacao = now();
                $carteira->save();
            }

            $transacao->delete();
        });
    }

    public function montarResumoCarteira(?PontosFidelidade $carteira): array
    {
        $config = $this->obterConfiguracao();
        $pontosAtuais = $carteira?->pontos_atuais ?? 0;
        $pontosAcumulados = $carteira?->pontos_acumulados ?? 0;

        $niveis = collect($config['niveis'] ?? [])->sortBy('minimo')->values();
        $nivelAtual = null;
        $proximoNivel = null;

        foreach ($niveis as $index => $nivel) {
            if ($pontosAcumulados >= ($nivel['minimo'] ?? 0)) {
                $nivelAtual = $nivel;
                $proximoNivel = $niveis[$index + 1] ?? null;
            }
        }

        if (! $nivelAtual && $niveis->isNotEmpty()) {
            $nivelAtual = $niveis->first();
            $proximoNivel = $niveis->get(1);
        }

        $progresso = 100.0;
        $faltamProximo = 0;

        if ($proximoNivel) {
            $intervalo = max(1, ($proximoNivel['minimo'] ?? 0) - ($nivelAtual['minimo'] ?? 0));
            $progresso = min(
                100,
                round((($pontosAcumulados - ($nivelAtual['minimo'] ?? 0)) / $intervalo) * 100, 2)
            );
            $faltamProximo = max(0, ($proximoNivel['minimo'] ?? 0) - $pontosAcumulados);
        }

        return [
            'pontos_atuais' => $pontosAtuais,
            'pontos_acumulados' => $pontosAcumulados,
            'valor_resgate_disponivel' => $this->calcularValorPorPontos($pontosAtuais),
            'pode_resgatar' => $pontosAtuais >= $config['resgate_minimo'],
            'faltam_para_resgate' => max(0, $config['resgate_minimo'] - $pontosAtuais),
            'resgate_minimo' => $config['resgate_minimo'],
            'valor_por_ponto' => $config['valor_por_ponto'],
            'pontos_por_real' => $config['pontos_por_real'],
            'maximo_percentual_resgate' => $config['maximo_percentual_resgate'],
            'nivel_atual' => $nivelAtual,
            'proximo_nivel' => $proximoNivel,
            'progresso_percentual' => $proximoNivel ? $progresso : 100,
            'faltam_para_proximo' => $faltamProximo,
            'ultima_atualizacao' => $carteira?->ultima_atualizacao,
        ];
    }

    public function calcularPontosPrevistos(float $valorFinal): int
    {
        return $this->calcularPontosPorValor($valorFinal);
    }

    protected function buscarTransacaoCredito(Agendamento $agendamento): ?TransacaoPonto
    {
        return $agendamento->transacoesPontos()
            ->where('tipo', 'CREDITO')
            ->where('agendamento_id', $agendamento->id)
            ->first();
    }

    protected function buscarTransacaoDebito(Agendamento $agendamento): ?TransacaoPonto
    {
        return $agendamento->transacoesPontos()
            ->where('tipo', 'DEBITO')
            ->where('agendamento_id', $agendamento->id)
            ->first();
    }
}
