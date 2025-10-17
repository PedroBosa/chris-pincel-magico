<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Agendamento;
use App\Models\Servico;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DisponibilidadeController extends Controller
{
    public function __invoke(Request $request, Servico $servico): JsonResponse
    {
        $data = $request->date('data', now());

        $agendamentos = Agendamento::query()
            ->where('servico_id', $servico->getKey())
            ->whereDate('data_hora_inicio', $data)
            ->get(['data_hora_inicio', 'data_hora_fim']);

        return response()->json([
            'servico' => $servico->only(['id', 'nome', 'duracao_minutos']),
            'data' => $data->toDateString(),
            'agendamentos' => $agendamentos,
        ]);
    }
}
