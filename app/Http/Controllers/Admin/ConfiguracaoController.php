<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Configuracao;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ConfiguracaoController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Configuracao::class);

        $configuracoes = Configuracao::query()
            ->orderBy('chave')
            ->get();

        return view('admin.configuracoes.index', compact('configuracoes'));
    }

    public function update(Request $request): RedirectResponse
    {
        $this->authorize('update', Configuracao::class);

        $dados = $request->validate([
            'configuracoes' => ['required', 'array'],
            'configuracoes.*.valor' => ['nullable'],
        ]);

        foreach ($dados['configuracoes'] as $chave => $entrada) {
            $valor = $entrada['valor'] ?? '';
            
            Configuracao::query()->updateOrCreate(
                ['chave' => $chave],
                ['valor' => ['valor' => $valor]]
            );
        }

        // Limpa o cache para forçar recarregar as configurações
        \Cache::flush();

        return back()->with('status', 'Configurações atualizadas com sucesso! ✅');
    }
}
