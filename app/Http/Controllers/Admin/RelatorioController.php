<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agendamento;
use App\Models\Pagamento;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class RelatorioController extends Controller
{
    public function index(Request $request): View
    {
        $inicio = $request->has('inicio')
            ? \Carbon\Carbon::parse($request->input('inicio'))
            : now()->startOfMonth();
        
        $fim = $request->has('fim')
            ? \Carbon\Carbon::parse($request->input('fim'))
            : now();

        $agendamentos = Agendamento::query()
            ->with(['cliente', 'servico'])
            ->whereBetween('data_hora_inicio', [$inicio->startOfDay(), $fim->endOfDay()])
            ->orderBy('data_hora_inicio')
            ->get();

        // Estatísticas
        $totalAgendamentos = $agendamentos->count();
        $agendamentosPorStatus = $agendamentos->groupBy('status')->map->count();
        $faturamentoTotal = $agendamentos->sum('valor_total');
        $ticketMedio = $totalAgendamentos > 0 ? $faturamentoTotal / $totalAgendamentos : 0;

        return view('admin.relatorios.index', compact(
            'agendamentos', 
            'inicio', 
            'fim',
            'totalAgendamentos',
            'agendamentosPorStatus',
            'faturamentoTotal',
            'ticketMedio'
        ));
    }
}
