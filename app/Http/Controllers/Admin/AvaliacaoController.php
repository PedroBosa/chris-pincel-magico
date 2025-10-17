<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Avaliacao;
use Illuminate\Http\Request;

class AvaliacaoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Avaliacao::with(['agendamento.servico', 'cliente'])
            ->orderBy('created_at', 'desc');

        // Filtro por status de publicação
        if ($request->has('status')) {
            if ($request->status === 'publicado') {
                $query->where('publicado', true);
            } elseif ($request->status === 'pendente') {
                $query->where('publicado', false);
            }
        }

        // Filtro por nota
        if ($request->has('nota') && $request->nota !== '') {
            $query->where('nota', $request->nota);
        }

        // Busca por nome do cliente ou comentário
        if ($request->has('busca') && $request->busca !== '') {
            $busca = $request->busca;
            $query->where(function($q) use ($busca) {
                $q->whereHas('cliente', function($q2) use ($busca) {
                    $q2->where('name', 'like', "%{$busca}%");
                })->orWhere('comentario', 'like', "%{$busca}%");
            });
        }

        $avaliacoes = $query->paginate(15);

        $estatisticas = [
            'total' => Avaliacao::count(),
            'publicadas' => Avaliacao::where('publicado', true)->count(),
            'pendentes' => Avaliacao::where('publicado', false)->count(),
            'media' => round(Avaliacao::avg('nota'), 1),
        ];

        return view('admin.avaliacoes.index', compact('avaliacoes', 'estatisticas'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Avaliacao $avaliacao)
    {
        $avaliacao->load(['agendamento.servico', 'cliente']);
        
        return view('admin.avaliacoes.show', compact('avaliacao'));
    }

    /**
     * Update the specified resource in storage (publicar/despublicar).
     */
    public function update(Request $request, Avaliacao $avaliacao)
    {
        $validated = $request->validate([
            'publicado' => 'required|boolean',
        ]);

        $avaliacao->publicado = $validated['publicado'];
        $avaliacao->publicado_em = $validated['publicado'] ? now() : null;
        $avaliacao->save();

        $status = $validated['publicado'] ? 'publicada' : 'despublicada';

        return redirect()
            ->back()
            ->with('success', "Avaliação {$status} com sucesso!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Avaliacao $avaliacao)
    {
        $avaliacao->delete();

        return redirect()
            ->route('admin.avaliacoes.index')
            ->with('success', 'Avaliação excluída com sucesso!');
    }

    /**
     * Publicar avaliação em lote
     */
    public function publicarEmLote(Request $request)
    {
        $validated = $request->validate([
            'avaliacoes' => 'required|array',
            'avaliacoes.*' => 'exists:avaliacoes,id',
        ]);

        $count = Avaliacao::whereIn('id', $validated['avaliacoes'])
            ->update([
                'publicado' => true,
                'publicado_em' => now(),
            ]);

        return redirect()
            ->back()
            ->with('success', "{$count} avaliação(ões) publicada(s) com sucesso!");
    }
}
