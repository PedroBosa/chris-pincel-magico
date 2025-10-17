<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agendamento;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    public function index(Request $request): View
    {
        // Pega a data da query string ou usa hoje
        $data = $request->has('data') 
            ? \Carbon\Carbon::parse($request->input('data'))
            : now();

        $agendamentos = Agendamento::query()
            ->with(['cliente', 'servico'])
            ->whereDate('data_hora_inicio', $data->format('Y-m-d'))
            ->orderBy('data_hora_inicio')
            ->get();

        return view('admin.agenda.index', compact('agendamentos', 'data'));
    }

    public function semanal(Request $request): View
    {
        // Pega a data da query string ou usa hoje
        $dataBase = $request->has('data') 
            ? \Carbon\Carbon::parse($request->input('data'))
            : now();

        // Calcula início e fim da semana (segunda a domingo)
        $inicioSemana = $dataBase->copy()->startOfWeek(\Carbon\Carbon::MONDAY);
        $fimSemana = $dataBase->copy()->endOfWeek(\Carbon\Carbon::SUNDAY);

        // Array com os 7 dias da semana
        $diasSemana = collect();
        for ($i = 0; $i < 7; $i++) {
            $diasSemana->push($inicioSemana->copy()->addDays($i));
        }

        // Busca agendamentos de toda a semana
        $agendamentosSemana = Agendamento::query()
            ->with(['cliente', 'servico'])
            ->whereBetween('data_hora_inicio', [$inicioSemana, $fimSemana])
            ->orderBy('data_hora_inicio')
            ->get();

        // Organiza agendamentos por dia (index 0-6)
        $agendamentos = [];
        foreach ($diasSemana as $index => $dia) {
            $agendamentos[$index] = $agendamentosSemana->filter(function($agendamento) use ($dia) {
                return $agendamento->data_hora_inicio->isSameDay($dia);
            })->values();
        }

        return view('admin.agenda.semanal', compact('inicioSemana', 'fimSemana', 'diasSemana', 'agendamentos'));
    }
}
