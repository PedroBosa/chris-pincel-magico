<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Servico;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ServicoController extends Controller
{
    public function index(Request $request): View
    {
        $servicos = Servico::query()
            ->where('ativo', true)
            ->orderBy('nome')
            ->paginate(12);

        return view('site.servicos.index', compact('servicos'));
    }

    public function show(Servico $servico): View
    {
        abort_unless($servico->ativo, 404);

        return view('site.servicos.show', compact('servico'));
    }
}
