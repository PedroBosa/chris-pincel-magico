<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agendamento;
use App\Models\Servico;
use App\Models\User;
use App\Services\AgendamentoService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AgendamentoController extends Controller
{
    public function __construct(
        protected AgendamentoService $agendamentoService
    ) {}

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Agendamento::class);

        $agendamentos = Agendamento::query()
            ->with(['cliente', 'servico'])
            ->latest('data_hora_inicio')
            ->paginate(20);

        $servicos = Servico::where('ativo', true)->get();
        $clientes = User::where('is_admin', false)
            ->orderBy('name')
            ->get();

        return view('admin.agendamentos.index', compact('agendamentos', 'servicos', 'clientes'));
    }

    public function create(): View
    {
        $this->authorize('create', Agendamento::class);

        $servicos = Servico::where('ativo', true)->get();
        $clientes = User::where('is_admin', false)
            ->orderBy('name')
            ->get();

        return view('admin.agendamentos.create', compact('servicos', 'clientes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Agendamento::class);

        $validated = $request->validate([
            'cliente_id' => ['required', 'exists:users,id'],
            'servico_id' => ['required', 'exists:servicos,id'],
            'data_hora_inicio' => ['required', 'date'],
            'observacoes' => ['nullable', 'string', 'max:1000'],
        ]);

        $servico = Servico::findOrFail($validated['servico_id']);
        $dataHoraInicio = \Carbon\Carbon::parse($validated['data_hora_inicio']);

        // Validar disponibilidade usando o service
        $verificacao = $this->agendamentoService->verificarDisponibilidade(
            $dataHoraInicio,
            $validated['servico_id']
        );

        if (!$verificacao['disponivel']) {
            return back()
                ->withInput()
                ->withErrors(['data_hora_inicio' => $verificacao['motivo']]);
        }

        $agendamento = Agendamento::create([
            'user_id' => $validated['cliente_id'],
            'servico_id' => $validated['servico_id'],
            'data_hora_inicio' => $verificacao['data_hora_inicio'],
            'data_hora_fim' => $verificacao['data_hora_fim'],
            'valor_original' => $servico->preco,
            'valor_total' => $servico->preco,
            'valor_desconto' => 0,
            'status' => 'PENDENTE',
            'tipo' => 'NORMAL',
            'canal_origem' => 'ADMIN',
            'observacoes' => $validated['observacoes'] ?? null,
        ]);

        return redirect()
            ->route('admin.agendamentos.index')
            ->with('status', 'Agendamento criado com sucesso! ✅');
    }

    public function show(Agendamento $agendamento): View
    {
        $this->authorize('view', $agendamento);

        $agendamento->load(['cliente', 'servico', 'pagamentos']);

        return view('admin.agendamentos.show', compact('agendamento'));
    }

    public function updateStatus(Request $request, Agendamento $agendamento): RedirectResponse
    {
        $this->authorize('update', $agendamento);

        $validated = $request->validate([
            'status' => ['required', 'in:PENDENTE,CONFIRMADO,CONCLUIDO,CANCELADO,NO_SHOW'],
        ]);

        $agendamento->update($validated);

        return back()->with('status', 'Agendamento atualizado.');
    }

    public function edit(Agendamento $agendamento): View
    {
        $this->authorize('update', $agendamento);

        $agendamento->load(['cliente', 'servico']);

        return view('admin.agendamentos.edit', compact('agendamento'));
    }

    public function update(Request $request, Agendamento $agendamento): RedirectResponse
    {
        $this->authorize('update', $agendamento);

        $validated = $request->validate([
            'cliente_id' => ['required', 'exists:users,id'],
            'servico_id' => ['required', 'exists:servicos,id'],
            'data_hora_inicio' => ['required', 'date'],
            'observacoes' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', 'in:PENDENTE,CONFIRMADO,CONCLUIDO,CANCELADO,NO_SHOW'],
        ]);

        // Renomeia cliente_id para user_id
        $validated['user_id'] = $validated['cliente_id'];
        unset($validated['cliente_id']);

        $agendamento->update($validated);

        return redirect()
            ->route('admin.agendamentos.index')
            ->with('status', 'Agendamento atualizado com sucesso!');
    }

    public function destroy(Agendamento $agendamento): RedirectResponse
    {
        $this->authorize('delete', $agendamento);

        $agendamento->delete();

        return redirect()
            ->route('admin.agendamentos.index')
            ->with('status', 'Agendamento excluído com sucesso!');
    }
}

