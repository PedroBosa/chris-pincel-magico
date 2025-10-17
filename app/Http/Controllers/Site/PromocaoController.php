<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Promocao;
use Illuminate\Contracts\View\View;

class PromocaoController extends Controller
{
    public function index(): View
    {
        $promocoes = Promocao::query()
            ->where('ativo', true)
            ->orderByDesc('inicio_vigencia')
            ->paginate(12);

        return view('site.promocoes.index', compact('promocoes'));
    }

    public function show(string $slug): View
    {
        $promocao = Promocao::query()
            ->where('slug', $slug)
            ->where('ativo', true)
            ->firstOrFail();

        return view('site.promocoes.show', compact('promocao'));
    }
}
