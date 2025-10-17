<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ContaController extends Controller
{
    public function index(Request $request): View
    {
        $usuario = $request->user();
        $agendamentos = $usuario?->agendamentos()
            ->with(['servico', 'avaliacao'])
            ->latest('data_hora_inicio')
            ->limit(20)
            ->get();

        return view('site.conta.index', [
            'usuario' => $usuario,
            'agendamentos' => $agendamentos,
        ]);
    }
}
