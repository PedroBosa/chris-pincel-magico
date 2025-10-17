<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promocao;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PromocaoController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Promocao::class);

        $promocoes = Promocao::query()->latest('inicio_vigencia')->paginate(20);

        return view('admin.promocoes.index', compact('promocoes'));
    }

    public function create(): View
    {
        $this->authorize('create', Promocao::class);

        return view('admin.promocoes.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Promocao::class);

        $data = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:promocoes,slug'],
            'descricao' => ['nullable', 'string'],
            'tipo' => ['required', 'in:VALOR,PERCENTUAL'],
            'valor_desconto' => ['nullable', 'numeric', 'min:0'],
            'percentual_desconto' => ['nullable', 'integer', 'between:0,100'],
            'codigo_cupom' => ['nullable', 'string', 'max:40', 'unique:promocoes,codigo_cupom'],
            'inicio_vigencia' => ['nullable', 'date'],
            'fim_vigencia' => ['nullable', 'date', 'after_or_equal:inicio_vigencia'],
            'limite_uso' => ['nullable', 'integer', 'min:0'],
            'restricoes' => ['nullable', 'array'],
        ], [
            'slug.unique' => 'Este slug já está em uso. Tente: ' . $request->slug . '-' . date('Y'),
            'codigo_cupom.unique' => 'Este código de cupom já está em uso. Escolha outro código único.',
        ]);

        // Define ativo como true se não for enviado (checkbox não marcado)
        $data['ativo'] = $request->has('ativo');

        Promocao::create($data);

        return redirect()->route('admin.promocoes.index')->with('status', 'Promoção criada com sucesso.');
    }

    public function edit(Promocao $promocao): View
    {
        $this->authorize('view', $promocao);

        return view('admin.promocoes.edit', compact('promocao'));
    }

    public function update(Request $request, Promocao $promocao): RedirectResponse
    {
        $this->authorize('update', $promocao);

        $data = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', "unique:promocoes,slug,{$promocao->id}"],
            'descricao' => ['nullable', 'string'],
            'tipo' => ['required', 'in:VALOR,PERCENTUAL'],
            'valor_desconto' => ['nullable', 'numeric', 'min:0'],
            'percentual_desconto' => ['nullable', 'integer', 'between:0,100'],
            'codigo_cupom' => ['nullable', 'string', 'max:40', "unique:promocoes,codigo_cupom,{$promocao->id}"],
            'inicio_vigencia' => ['nullable', 'date'],
            'fim_vigencia' => ['nullable', 'date', 'after_or_equal:inicio_vigencia'],
            'limite_uso' => ['nullable', 'integer', 'min:0'],
            'restricoes' => ['nullable', 'array'],
        ]);

        // Define ativo com base no checkbox
        $data['ativo'] = $request->has('ativo');

        $promocao->update($data);

        return redirect()->route('admin.promocoes.index')->with('status', 'Promoção atualizada com sucesso.');
    }

    public function destroy(Promocao $promocao): RedirectResponse
    {
        $this->authorize('delete', $promocao);

        $promocao->delete();

        return redirect()->route('admin.promocoes.index')->with('status', 'Promoção excluída com sucesso.');
    }
}
