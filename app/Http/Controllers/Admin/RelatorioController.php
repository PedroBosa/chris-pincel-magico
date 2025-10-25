<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agendamento;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        if ($inicio->greaterThan($fim)) {
            [$inicio, $fim] = [$fim->copy()->startOfDay(), $inicio->copy()->endOfDay()];
        }

        $rangeInicio = $inicio->copy()->startOfDay();
        $rangeFim = $fim->copy()->endOfDay();

        $baseQuery = Agendamento::query()
            ->whereBetween('data_hora_inicio', [$rangeInicio, $rangeFim]);

        $agendamentos = (clone $baseQuery)
            ->select(['id', 'user_id', 'servico_id', 'status', 'valor_total', 'data_hora_inicio'])
            ->with([
                'cliente:id,name',
                'servico:id,nome'
            ])
            ->orderBy('data_hora_inicio')
            ->get();

        $totalAgendamentos = (clone $baseQuery)->count();

        $agendamentosPorStatus = (clone $baseQuery)
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $faturamentoTotal = (clone $baseQuery)->sum('valor_total');
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
