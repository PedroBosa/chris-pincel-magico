<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Services\ProgramaFidelidadeService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

use function collect;

class ContaController extends Controller
{
    public function __construct(
        protected ProgramaFidelidadeService $fidelidadeService
    ) {}

    public function index(Request $request): View
    {
        $usuario = $request->user();
        $agendamentos = $usuario?->agendamentos()
            ->with(['servico', 'avaliacao'])
            ->latest('data_hora_inicio')
            ->limit(20)
            ->get();

        $agendamentosConcluidos = $agendamentos
            ? $agendamentos->filter(fn ($agendamento) => strcasecmp($agendamento->status, 'CONCLUIDO') === 0)
            : collect();

        $carteira = $usuario ? $this->fidelidadeService->obterOuCriarCarteira($usuario) : null;
        $resumoFidelidade = $this->fidelidadeService->montarResumoCarteira($carteira);
        $transacoesPontos = $carteira
            ? $carteira->transacoes()
                ->latest('registrado_em')
                ->limit(10)
                ->get()
            : collect();

        return view('site.conta.index', [
            'usuario' => $usuario,
            'agendamentos' => $agendamentos,
            'agendamentosConcluidos' => $agendamentosConcluidos,
            'resumoFidelidade' => $resumoFidelidade,
            'transacoesPontos' => $transacoesPontos,
        ]);
    }
}
