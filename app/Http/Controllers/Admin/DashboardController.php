<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agendamento;
use App\Models\Pagamento;
use App\Models\User;
use App\Models\Servico;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(): View
    {
        $this->authorize('access-admin');

        // Agendamentos Pendentes
        $agendamentosPendentes = Agendamento::where('status', 'PENDENTE')->count();
        
        // Faturamento Mensal (apenas pagamentos capturados)
        $faturamentoMensal = Pagamento::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('status', 'approved')
            ->sum('valor_capturado');

        // Total de Agendamentos
        $totalAgendamentos = Agendamento::count();

        // Total de Clientes (usuários não administradores)
        $totalClientes = User::where('is_admin', false)->count();

        // Próximos Agendamentos (próximos 5 dias)
        $proximosAgendamentos = Agendamento::with(['usuario', 'servico'])
            ->where('data_hora_inicio', '>=', now())
            ->where('status', '!=', 'CANCELADO')
            ->orderBy('data_hora_inicio')
            ->take(5)
            ->get();

        // Serviços Populares (top 5 mais agendados)
        $servicosPopulares = Servico::withCount(['agendamentos' => function ($query) {
                $query->where('status', '!=', 'CANCELADO');
            }])
            ->orderBy('agendamentos_count', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', [
            'agendamentosPendentes' => $agendamentosPendentes,
            'faturamentoMensal' => $faturamentoMensal,
            'totalAgendamentos' => $totalAgendamentos,
            'totalClientes' => $totalClientes,
            'proximosAgendamentos' => $proximosAgendamentos,
            'servicosPopulares' => $servicosPopulares,
        ]);
    }

    public function __invoke(): View
    {
        return $this->index();
    }
}
