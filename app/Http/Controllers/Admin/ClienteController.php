<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agendamento;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', User::class);

        $clientes = User::query()
            ->select(['id', 'name', 'email', 'created_at', 'updated_at'])
            ->withCount('agendamentos')
            ->when($request->filled('search'), function ($query) use ($request) {
                $term = "%{$request->string('search')}%";

                $query->where(function ($inner) use ($term) {
                    $inner->where('name', 'like', $term)
                        ->orWhere('email', 'like', $term);
                });
            })
            ->paginate(20);

        $clienteIds = $clientes->getCollection()->pluck('id');

        if ($clienteIds->isNotEmpty()) {
            $recentAgendamentos = Agendamento::query()
                ->select(['id', 'user_id', 'servico_id', 'status', 'data_hora_inicio'])
                ->whereIn('user_id', $clienteIds)
                ->latest('data_hora_inicio')
                ->with(['servico:id,nome'])
                ->get()
                ->groupBy('user_id');

            $clientes->setCollection(
                $clientes->getCollection()->map(function (User $cliente) use ($recentAgendamentos) {
                    $cliente->setRelation(
                        'recentAgendamentos',
                        $recentAgendamentos->get($cliente->id, collect())->take(5)
                    );

                    return $cliente;
                })
            );
        }

        return view('admin.clientes.index', compact('clientes'));
    }

    public function show(User $cliente): View
    {
        $this->authorize('view', $cliente);

        $cliente->load(['agendamentos' => fn ($query) => $query->latest('data_hora_inicio')->limit(10)]);

        return view('admin.clientes.show', compact('cliente'));
    }

    public function update(Request $request, User $cliente): RedirectResponse
    {
        $this->authorize('update', $cliente);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $cliente->id],
            'telefone' => ['nullable', 'string', 'max:20'],
            'data_nascimento' => ['nullable', 'date'],
        ]);

        $cliente->update($validated);

        return redirect()
            ->route('admin.clientes.index')
            ->with('status', 'Cliente atualizado com sucesso!');
    }

    public function destroy(User $cliente): RedirectResponse
    {
        $this->authorize('delete', $cliente);

        $cliente->delete();

        return redirect()
            ->route('admin.clientes.index')
            ->with('status', 'Cliente excluído com sucesso!');
    }
}

