<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Agendamento;
use App\Models\Avaliacao;
use Illuminate\Http\Request;

class AvaliacaoController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'agendamento_id' => 'required|exists:agendamentos,id',
            'nota' => 'required|integer|min:1|max:5',
            'comentario' => 'nullable|string|max:1000',
        ]);

        // Verificar se o agendamento pertence ao usuário
        $agendamento = Agendamento::findOrFail($validated['agendamento_id']);
        
        if ($agendamento->user_id !== auth()->id()) {
            abort(403, 'Você não tem permissão para avaliar este agendamento.');
        }

        // Verificar se o agendamento está concluído
        if ($agendamento->status !== 'concluido') {
            return back()->with('error', 'Você só pode avaliar agendamentos concluídos.');
        }

        // Verificar se já existe avaliação
        if ($agendamento->avaliacao()->exists()) {
            return back()->with('error', 'Você já avaliou este agendamento.');
        }

        // Criar avaliação
        Avaliacao::create([
            'agendamento_id' => $validated['agendamento_id'],
            'user_id' => auth()->id(),
            'nota' => $validated['nota'],
            'comentario' => $validated['comentario'],
            'publicado' => false, // Aguarda moderação
            'publicado_em' => null,
        ]);

        return back()->with('success', 'Avaliação enviada com sucesso! Ela será publicada após moderação.');
    }
}
