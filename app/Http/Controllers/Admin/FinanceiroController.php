<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pagamento;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class FinanceiroController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Pagamento::class);

        // Filtros
        $mes = $request->get('mes', now()->month);
        $ano = $request->get('ano', now()->year);
        $status = $request->get('status');

        // Query base
        $query = Pagamento::with(['agendamento.usuario', 'agendamento.servico']);

        // Aplicar filtros
        if ($mes && $ano) {
            $query->whereMonth('created_at', $mes)
                  ->whereYear('created_at', $ano);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $pagamentos = $query->latest('created_at')->paginate(30);

        // Estatísticas do mês atual
        $totalMes = Pagamento::whereMonth('created_at', $mes)
            ->whereYear('created_at', $ano)
            ->where('status', 'approved')
            ->sum('valor_capturado');

        $totalPagamentos = Pagamento::whereMonth('created_at', $mes)
            ->whereYear('created_at', $ano)
            ->count();

        $ticketMedio = $totalPagamentos > 0 ? $totalMes / $totalPagamentos : 0;

        // Pagamentos pendentes
        $pagamentosPendentes = Pagamento::where('status', 'pending')->count();

        // Total capturado (todos os tempos)
        $totalCapturado = Pagamento::where('status', 'approved')
            ->sum('valor_capturado');

        // Métodos de pagamento mais usados
        $metodosPopulares = Pagamento::selectRaw('forma_pagamento, COUNT(*) as total')
            ->whereNotNull('forma_pagamento')
            ->groupBy('forma_pagamento')
            ->orderBy('total', 'desc')
            ->take(5)
            ->get();

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
