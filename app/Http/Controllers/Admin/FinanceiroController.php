<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pagamento;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FinanceiroController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Pagamento::class);

        // Filtros
        $mes = $request->get('mes', now()->month);
        $ano = $request->get('ano', now()->year);
        $statusFiltro = $request->get('status');
        $statusNormalizado = $statusFiltro ? Str::upper($statusFiltro) : null;

        // Query base
        $query = Pagamento::query()
            ->select(['id', 'agendamento_id', 'status', 'valor_total', 'valor_capturado', 'forma_pagamento', 'gateway', 'created_at'])
            ->with([
                'agendamento' => function ($agendamentoQuery) {
                    $agendamentoQuery->select(['id', 'user_id', 'servico_id', 'data_hora_inicio'])
                        ->with([
                            'usuario:id,name,email',
                            'servico:id,nome'
                        ]);
                }
            ]);

        // Aplicar filtros
        if ($mes && $ano) {
            $query->whereMonth('created_at', $mes)
                  ->whereYear('created_at', $ano);
        }

        if ($statusNormalizado) {
            $query->where('status', $statusNormalizado);
        }

        $pagamentos = $query->latest('created_at')->paginate(30);

        // Estatísticas do mês atual
        $totalMes = Pagamento::whereMonth('created_at', $mes)
            ->whereYear('created_at', $ano)
            ->whereIn('status', ['PAID', 'APPROVED'])
            ->sum('valor_capturado');

        $totalPagamentos = Pagamento::whereMonth('created_at', $mes)
            ->whereYear('created_at', $ano)
            ->count();

        $ticketMedio = $totalPagamentos > 0 ? $totalMes / $totalPagamentos : 0;

        // Pagamentos pendentes
        $pagamentosPendentes = Pagamento::where('status', 'PENDING')->count();

        // Total capturado (todos os tempos)
        $totalCapturado = Pagamento::whereIn('status', ['PAID', 'APPROVED'])
            ->sum('valor_capturado');

        // Métodos de pagamento mais usados
        $metodosPopulares = Pagamento::selectRaw('forma_pagamento, COUNT(*) as total')
            ->whereNotNull('forma_pagamento')
            ->groupBy('forma_pagamento')
            ->orderBy('total', 'desc')
            ->take(5)
            ->get();

        $status = $statusFiltro;

        return view('admin.financeiro.index', compact(
            'pagamentos',
            'totalMes',
            'totalPagamentos',
            'ticketMedio',
            'pagamentosPendentes',
            'totalCapturado',
            'metodosPopulares',
            'mes',
            'ano',
            'status'
        ));
    }
}
