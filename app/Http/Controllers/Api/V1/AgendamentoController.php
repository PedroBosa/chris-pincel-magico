<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Agendamento;
use App\Models\Servico;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AgendamentoController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'servico_id' => ['required', 'exists:servicos,id'],
            'data_hora_inicio' => ['required', 'date'],
            'valor_total' => ['required', 'numeric', 'min:0'],
            'valor_sinal' => ['nullable', 'numeric', 'min:0'],
        ]);

        $servico = Servico::query()->findOrFail($data['servico_id']);

        $agendamento = Agendamento::create([
            'user_id' => $data['user_id'],
            'servico_id' => $servico->getKey(),
            'status' => 'PENDENTE',
            'tipo' => 'NORMAL',
            'data_hora_inicio' => $data['data_hora_inicio'],
            'data_hora_fim' => now()->parse($data['data_hora_inicio'])->addMinutes($servico->duracao_minutos),
            'valor_total' => $data['valor_total'],
            'valor_sinal' => $data['valor_sinal'] ?? 0,
        ]);

        return response()->json($agendamento, Response::HTTP_CREATED);
    }
}
