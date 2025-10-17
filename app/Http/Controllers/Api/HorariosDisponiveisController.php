<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AgendamentoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HorariosDisponiveisController extends Controller
{
    public function __construct(
        protected AgendamentoService $agendamentoService
    ) {}

    /**
     * Retorna horários disponíveis para uma data e serviço específicos
     */
    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'data' => ['required', 'date', 'after_or_equal:today'],
            'servico_id' => ['required', 'exists:servicos,id'],
        ]);

        $data = \Carbon\Carbon::parse($validated['data']);
        $servicoId = $validated['servico_id'];

        $horarios = $this->agendamentoService->horariosDisponiveis($data, $servicoId);

        return response()->json([
            'success' => true,
            'data' => $data->format('Y-m-d'),
            'dia_semana' => $data->locale('pt_BR')->isoFormat('dddd'),
            'horarios' => $horarios,
            'total' => count($horarios)
        ]);
    }
}
