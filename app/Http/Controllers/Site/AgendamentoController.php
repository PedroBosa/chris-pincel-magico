<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Agendamento;
use App\Models\Servico;
use App\Services\AgendamentoService;
use App\Services\ProgramaFidelidadeService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Throwable;

class AgendamentoController extends Controller
{
    public function __construct(
        protected AgendamentoService $agendamentoService,
        protected ProgramaFidelidadeService $fidelidadeService
    ) {}

    public function create(Request $request): View
    {
        $carregamentoServicosErro = false;

        try {
            $servicos = Servico::query()
                ->where('ativo', true)
                ->orderBy('nome')
                ->get(['id', 'nome', 'duracao_minutos', 'preco', 'preco_retoque']);
        } catch (QueryException|Throwable $e) {
            Log::error('Falha ao carregar serviços para agendamento', [
                'mensagem' => $e->getMessage(),
            ]);

            $servicos = collect();
            $carregamentoServicosErro = true;
        }

        $usuario = $request->user();
        $resumoFidelidade = null;

        if ($usuario) {
            $carteira = $this->fidelidadeService->obterOuCriarCarteira($usuario);
            $resumoFidelidade = $this->fidelidadeService->montarResumoCarteira($carteira);
        }

        return view('site.agendamentos.create', [
            'servicos' => $servicos,
            'resumoFidelidade' => $resumoFidelidade,
            'carregamentoServicosErro' => $carregamentoServicosErro,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'servico_id' => ['required', 'exists:servicos,id'],
            'promocao_id' => ['nullable', 'exists:promocoes,id'],
            'codigo_cupom' => ['nullable', 'string'],
            'data' => ['required', 'date', 'after_or_equal:today'],
            'hora' => ['required', 'date_format:H:i'],
            'forma_pagamento' => ['required', 'in:pix,credit_card,debit_card'],
            'observacoes' => ['nullable', 'string', 'max:500'],
            'usar_pontos' => ['nullable', 'integer', 'min:0'],
        ]);

        // VALIDAÇÃO 1: Limitar agendamentos simultâneos pendentes
        $agendamentosPendentes = Agendamento::where('user_id', Auth::id())
            ->whereIn('status', ['PENDENTE', 'CONFIRMADO'])
            ->where('data_hora_inicio', '>=', now())
            ->count();

        if ($agendamentosPendentes >= 3) {
            return back()
                ->withInput()
                ->withErrors([
                    'geral' => 'Você já possui 3 agendamentos ativos. Conclua ou cancele algum para fazer um novo.'
                ])
                ->with('error', 'Limite de agendamentos ativos atingido.');
        }

        // Cria o datetime de início
        $dataHoraInicio = \Carbon\Carbon::parse($validated['data'] . ' ' . $validated['hora']);
        
        // VALIDAÇÃO 2: Não permitir agendar no passado
        if ($dataHoraInicio->isPast()) {
            return back()
                ->withInput()
                ->withErrors([
                    'data' => 'Não é possível agendar em datas passadas.'
                ])
                ->with('error', 'Data/hora inválida.');
        }

        // VALIDAÇÃO 3: Não permitir múltiplos agendamentos na mesma data/hora
        $agendamentoDuplicado = Agendamento::where('user_id', Auth::id())
            ->where('data_hora_inicio', $dataHoraInicio)
            ->whereNotIn('status', ['CANCELADO'])
            ->exists();

        if ($agendamentoDuplicado) {
            return back()
                ->withInput()
                ->withErrors([
                    'hora' => 'Você já possui um agendamento neste horário.'
                ])
                ->with('error', 'Horário já ocupado por você.');
        }

        // VALIDAÇÃO 4: Verificar disponibilidade usando o AgendamentoService
        $verificacao = $this->agendamentoService->verificarDisponibilidade(
            $dataHoraInicio,
            $validated['servico_id']
        );

        if (!$verificacao['disponivel']) {
            return back()
                ->withInput()
                ->withErrors([
                    'data' => $verificacao['motivo']
                ])
                ->with('error', $verificacao['motivo']);
        }

        // Busca o serviço para obter duração e preço
        $servico = Servico::findOrFail($validated['servico_id']);
        $valorOriginal = $servico->preco;
        $valorDesconto = 0;
        $promocaoId = null;
        $codigoCupom = null;

        // Se tem promoção, valida e calcula desconto
        if ($request->filled('promocao_id') && $request->filled('codigo_cupom')) {
            $promocao = \App\Models\Promocao::find($request->promocao_id);
            
            if ($promocao && $promocao->ativo && $this->validarPromocao($promocao)) {
                $promocaoId = $promocao->id;
                $codigoCupom = strtoupper(trim($request->codigo_cupom));
                
                // Calcula desconto
                if ($promocao->tipo === 'PERCENTUAL') {
                    $valorDesconto = $valorOriginal * ($promocao->percentual_desconto / 100);
                } else {
                    $valorDesconto = min($promocao->valor_desconto, $valorOriginal);
                }
                
                // Incrementa uso (será confirmado após pagamento em produção)
                $promocao->increment('usos_realizados');
            }
        }

        $valorBase = max(0, $valorOriginal - $valorDesconto);
        $valorFinal = $valorBase;
        $valorDescontoFidelidade = 0.0;
        $pontosResgatados = 0;
        $metadadosExtras = [];
        $carteiraFidelidade = null;
        $pontosSolicitados = (int) ($validated['usar_pontos'] ?? 0);

        if (Auth::check() && $pontosSolicitados > 0) {
            $carteiraFidelidade = $this->fidelidadeService->obterOuCriarCarteira(Auth::user());
            $resgate = $this->fidelidadeService->validarResgate($carteiraFidelidade, $pontosSolicitados, $valorBase);

            if (! $resgate['valido']) {
                $mensagemErro = $resgate['mensagem'] ?? 'Não foi possível aplicar os seus pontos no momento.';

                return back()
                    ->withInput()
                    ->withErrors(['usar_pontos' => $mensagemErro])
                    ->with('error', $mensagemErro);
            }

            $pontosResgatados = $resgate['pontos'];
            $valorDescontoFidelidade = $resgate['valor_desconto'];
            $valorFinal = max(0, $valorBase - $valorDescontoFidelidade);
            $valorDesconto += $valorDescontoFidelidade;

            $metadadosExtras['fidelidade'] = [
                'pontos_resgatados' => $pontosResgatados,
                'valor_desconto_pontos' => $valorDescontoFidelidade,
            ];
        }

        $valorFinal = round($valorFinal, 2);
        $valorDesconto = round($valorDesconto, 2);
        $valorSinal = round($valorFinal * 0.30, 2); // 30% de sinal

        // Usa as datas validadas do service
        $dataHoraFim = $verificacao['data_hora_fim'];

        // Cria o agendamento
        $agendamento = Agendamento::create([
            'user_id' => Auth::id(),
            'servico_id' => $servico->id,
            'promocao_id' => $promocaoId,
            'codigo_cupom_usado' => $codigoCupom,
            'data_hora_inicio' => $verificacao['data_hora_inicio'],
            'data_hora_fim' => $dataHoraFim,
            'status' => 'PENDENTE',
            'tipo' => 'NORMAL',
            'canal_origem' => 'SITE',
            'valor_original' => ($promocaoId || $pontosResgatados > 0) ? $valorOriginal : null,
            'valor_desconto' => $valorDesconto,
            'valor_total' => $valorFinal,
            'valor_sinal' => $valorSinal,
            'forma_pagamento' => $validated['forma_pagamento'],
            'forma_pagamento_sinal' => $validated['forma_pagamento'],
            'observacoes' => $validated['observacoes'] ?? null,
            'metadados' => ! empty($metadadosExtras) ? $metadadosExtras : null,
        ]);

        if ($pontosResgatados > 0 && $carteiraFidelidade) {
            $this->fidelidadeService->registrarDebitoResgate(
                $agendamento,
                $carteiraFidelidade,
                $pontosResgatados,
                $valorDescontoFidelidade
            );
        }

        return redirect()
            ->route('agendamentos.create')
            ->with('agendamento_criado', $agendamento->id)
            ->with('success', 'Agendamento realizado com sucesso!');
    }

    private function validarPromocao(\App\Models\Promocao $promocao): bool
    {
        $agora = now();
        
        // Verifica vigência
        if ($promocao->inicio_vigencia && $agora->lt($promocao->inicio_vigencia)) {
            return false;
        }
        
        if ($promocao->fim_vigencia && $agora->gt($promocao->fim_vigencia)) {
            return false;
        }
        
        // Verifica limite de uso
        if ($promocao->limite_uso && $promocao->usos_realizados >= $promocao->limite_uso) {
            return false;
        }
        
        return true;
    }

    public function confirmation(Request $request): View
    {
        $agendamentoId = $request->session()->get('agendamento_id');
        
        if (!$agendamentoId) {
            abort(404, 'Agendamento não encontrado');
        }

        $agendamento = Agendamento::with(['servico', 'cliente'])
            ->where('user_id', Auth::id())
            ->findOrFail($agendamentoId);

        return view('site.agendamentos.confirmacao', compact('agendamento'));
    }
}
