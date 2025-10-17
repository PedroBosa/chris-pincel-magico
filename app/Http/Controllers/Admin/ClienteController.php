<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
            ->withCount('agendamentos')
            ->when($request->filled('search'), function ($query) use ($request) {
                $term = "%{$request->string('search')}%";

                $query->where(function ($inner) use ($term) {
                    $inner->where('name', 'like', $term)
                        ->orWhere('email', 'like', $term);
                });
            })
            ->paginate(20);

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

