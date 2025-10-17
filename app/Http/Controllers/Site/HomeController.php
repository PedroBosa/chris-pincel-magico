<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Servico;
use App\Models\User;
use App\Models\Agendamento;
use App\Models\Avaliacao;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(Request $request): View
    {
        $servicos = Servico::where('ativo', true)
            ->orderBy('nome')
            ->take(3)
            ->get();

        // Estatísticas reais
        $totalServicos = Servico::where('ativo', true)->count();
        $totalClientes = User::where('is_admin', false)->count();
        $totalAgendamentos = Agendamento::where('status', 'concluido')->count();
        $avaliacoesPublicadas = Avaliacao::where('publicado', true)->get();
        $mediaAvaliacoes = $avaliacoesPublicadas->count() > 0 
            ? round($avaliacoesPublicadas->avg('nota'), 1) 
            : 0;

        return view('site.home', compact(
            'servicos',
            'totalServicos',
            'totalClientes',
            'totalAgendamentos',
            'mediaAvaliacoes'
        ));
    }
}
