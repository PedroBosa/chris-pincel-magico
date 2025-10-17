<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Servico;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ServicoController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Servico::class);

        $servicos = Servico::query()->orderBy('nome')->paginate(15);

        return view('admin.servicos.index', compact('servicos'));
    }

    public function create(): View
    {
        $this->authorize('create', Servico::class);

        return view('admin.servicos.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Servico::class);

        $data = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:servicos,slug'],
            'descricao' => ['nullable', 'string'],
            'imagem_capa' => ['nullable', 'image', 'max:2048'],
            'duracao_minutos' => ['required', 'integer', 'min:15'],
            'preco' => ['required', 'numeric', 'min:0'],
            'preco_retoque' => ['nullable', 'numeric', 'min:0'],
            'dias_para_retoque' => ['required', 'integer', 'min:0'],
            'ativo' => ['boolean'],
        ]);

        // Upload da imagem
        if ($request->hasFile('imagem_capa')) {
            $data['imagem_capa'] = $request->file('imagem_capa')->store('servicos', 'public');
        }

        Servico::create($data);

        return redirect()->route('admin.servicos.index')->with('status', 'Serviço criado com sucesso.');
    }

    public function edit(Servico $servico): View
    {
        $this->authorize('view', $servico);

        return view('admin.servicos.edit', compact('servico'));
    }

    public function update(Request $request, Servico $servico): RedirectResponse
    {
        $this->authorize('update', $servico);

        $data = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', "unique:servicos,slug,{$servico->id}"],
            'descricao' => ['nullable', 'string'],
            'imagem_capa' => ['nullable', 'image', 'max:2048'],
            'duracao_minutos' => ['required', 'integer', 'min:15'],
            'preco' => ['required', 'numeric', 'min:0'],
            'preco_retoque' => ['nullable', 'numeric', 'min:0'],
            'dias_para_retoque' => ['required', 'integer', 'min:0'],
            'ativo' => ['boolean'],
        ]);

        // Upload da nova imagem
        if ($request->hasFile('imagem_capa')) {
            // Deletar imagem antiga se existir
            if ($servico->imagem_capa) {
                \Storage::disk('public')->delete($servico->imagem_capa);
            }
            $data['imagem_capa'] = $request->file('imagem_capa')->store('servicos', 'public');
        }

        $servico->update($data);

        return redirect()->route('admin.servicos.index')->with('status', 'Serviço atualizado com sucesso.');
    }

    public function destroy(Servico $servico): RedirectResponse
    {
        $this->authorize('delete', $servico);

        $servico->delete();

        return redirect()
            ->route('admin.servicos.index')
            ->with('status', 'Serviço excluído com sucesso!');
    }
}

