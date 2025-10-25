@extends('layouts.admin')

@section('title', 'Clientes')
@section('page-title', 'Clientes')
@section('page-description', 'Gerencie todos os clientes cadastrados')

@section('content')
<div class="mb-6">
    <form method="get" class="flex items-center gap-4">
        <div class="relative flex-1 max-w-md">
            <input 
                type="search" 
                id="search"
                name="search"
                value="{{ request('search') }}"
                placeholder="Buscar por nome ou e-mail..." 
                class="w-full pl-10 pr-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
            >
            <svg class="w-5 h-5 text-neutral-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
        <button type="submit" class="px-4 py-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 rounded-lg font-medium transition-colors">
            Filtrar
        </button>
        @if(request('search'))
            <a href="{{ route('admin.clientes.index') }}" class="px-4 py-2 text-neutral-600 hover:text-neutral-900 font-medium">
                Limpar
            </a>
        @endif
    </form>
</div>

<div class="bg-white rounded-2xl border border-neutral-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-neutral-50 border-b border-neutral-200">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-neutral-600 uppercase tracking-wider">Cliente</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-neutral-600 uppercase tracking-wider">Contato</th>
                    <th class="px-6 py-4 text-center text-xs font-bold text-neutral-600 uppercase tracking-wider">Agendamentos</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-neutral-600 uppercase tracking-wider">Cadastro</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-neutral-600 uppercase tracking-wider">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200">
                @forelse ($clientes as $cliente)
                    <tr class="hover:bg-neutral-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-primary-500 to-primary-400 flex items-center justify-center flex-shrink-0 shadow-lg">
                                    <span class="text-white font-bold text-lg">{{ strtoupper(substr($cliente->name, 0, 2)) }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-neutral-900">{{ $cliente->name }}</p>
                                    <p class="text-xs text-neutral-500">ID: #{{ $cliente->id }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-neutral-900">{{ $cliente->email }}</p>
                            @if($cliente->telefone)
                                <p class="text-xs text-neutral-500">{{ $cliente->telefone }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-blue-100 text-blue-700 font-bold text-sm">
                                {{ $cliente->agendamentos_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="text-sm text-neutral-900">{{ $cliente->created_at->format('d/m/Y') }}</p>
                            <p class="text-xs text-neutral-500">{{ $cliente->created_at->diffForHumans() }}</p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end gap-2">
                                <!-- Ver Perfil -->
                                <button 
                                    type="button"
                                    onclick="openModal('cliente-{{ $cliente->id }}')"
                                    class="p-2 text-primary-600 hover:text-primary-700 hover:bg-primary-50 rounded-lg transition-colors"
                                    title="Ver Perfil"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>

                                <!-- Editar -->
                                <button 
                                    type="button"
                                    onclick="openModal('editar-cliente-{{ $cliente->id }}')"
                                    class="p-2 text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-colors"
                                    title="Editar"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>

                                <!-- Deletar -->
                                <button 
                                    type="button"
                                    onclick="openModal('deletar-cliente-{{ $cliente->id }}')"
                                    class="p-2 text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors"
                                    title="Deletar"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-neutral-100 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <p class="text-neutral-600 font-medium">
                                    @if(request('search'))
                                        Nenhum cliente encontrado com "{{ request('search') }}"
                                    @else
                                        Nenhum cliente cadastrado
                                    @endif
                                </p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($clientes->hasPages())
        <div class="px-6 py-4 border-t border-neutral-200">
            {{ $clientes->links() }}
        </div>
    @endif
</div>

<!-- Modals fora da tabela -->
@foreach ($clientes as $cliente)
    <x-modal id="cliente-{{ $cliente->id }}" title="Detalhes do Cliente" size="3xl">
        <div class="space-y-6">
            <!-- Header com Avatar -->
            <div class="flex items-center gap-6 p-6 bg-gradient-to-br from-primary-50 to-white rounded-xl border border-primary-100">
                <div class="w-20 h-20 rounded-full bg-gradient-to-br from-primary-500 to-primary-400 flex items-center justify-center shadow-lg">
                    <span class="text-white font-bold text-3xl">{{ strtoupper(substr($cliente->name, 0, 2)) }}</span>
                </div>
                <div class="flex-1">
                    <h4 class="text-2xl font-bold text-neutral-900 mb-1">{{ $cliente->name }}</h4>
                    <div class="flex items-center gap-4 text-sm text-neutral-600">
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            {{ $cliente->email }}
                        </span>
                        @if($cliente->telefone)
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                {{ $cliente->telefone }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-3xl font-bold text-primary-600">{{ $cliente->agendamentos_count }}</div>
                    <div class="text-xs text-neutral-600 uppercase tracking-wider">Agendamentos</div>
                </div>
            </div>

            <!-- Informações do Cliente -->
            <div class="grid grid-cols-2 gap-4">
                <div class="p-4 bg-neutral-50 rounded-lg">
                    <div class="text-xs font-semibold text-neutral-600 uppercase tracking-wider mb-1">Cliente desde</div>
                    <div class="text-lg font-bold text-neutral-900">{{ $cliente->created_at->format('d/m/Y') }}</div>
                    <div class="text-xs text-neutral-500">{{ $cliente->created_at->diffForHumans() }}</div>
                </div>
                <div class="p-4 bg-neutral-50 rounded-lg">
                    <div class="text-xs font-semibold text-neutral-600 uppercase tracking-wider mb-1">Último acesso</div>
                    <div class="text-lg font-bold text-neutral-900">
                        {{ $cliente->updated_at->format('d/m/Y') }}
                    </div>
                    <div class="text-xs text-neutral-500">{{ $cliente->updated_at->diffForHumans() }}</div>
                </div>
            </div>

            <!-- Agendamentos Recentes -->
            @php
                $agendamentosRecentes = $cliente->getRelation('recentAgendamentos') ?? collect();
            @endphp

            @if($cliente->agendamentos_count > 0 && $agendamentosRecentes->isNotEmpty())
                <div>
                    <h5 class="font-bold text-neutral-900 mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Últimos Agendamentos
                    </h5>
                    <div class="space-y-2 max-h-64 overflow-y-auto">
                        @foreach($agendamentosRecentes as $agendamento)
                            <div class="p-3 bg-neutral-50 rounded-lg flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="font-medium text-neutral-900">{{ $agendamento->servico->nome ?? 'N/A' }}</div>
                                    <div class="text-sm text-neutral-600">
                                        {{ $agendamento->data_hora_inicio->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                                @php
                                    $statusColors = [
                                        'PENDENTE' => 'bg-amber-100 text-amber-700 border-amber-200',
                                        'CONFIRMADO' => 'bg-blue-100 text-blue-700 border-blue-200',
                                        'CONCLUIDO' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                        'CANCELADO' => 'bg-red-100 text-red-700 border-red-200',
                                    ];
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-semibold border {{ $statusColors[$agendamento->status] ?? 'bg-neutral-100 text-neutral-700 border-neutral-200' }}">
                                    {{ $agendamento->status }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <x-slot name="footer">
            <button 
                type="button"
                onclick="closeModal('cliente-{{ $cliente->id }}')"
                class="px-4 py-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 rounded-lg font-medium transition-colors"
            >
                Fechar
            </button>
            <a 
                href="mailto:{{ $cliente->email }}"
                class="px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg font-medium transition-colors inline-flex items-center gap-2"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                Enviar E-mail
            </a>
        </x-slot>
    </x-modal>

    <!-- Modal de Editar Cliente -->
    <x-modal id="editar-cliente-{{ $cliente->id }}" title="Editar Cliente" size="2xl">
        <form method="POST" action="{{ route('admin.clientes.update', $cliente) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- Nome -->
            <div>
                <label for="name_{{ $cliente->id }}" class="block text-sm font-semibold text-neutral-700 mb-2">
                    Nome Completo *
                </label>
                <input 
                    type="text" 
                    id="name_{{ $cliente->id }}" 
                    name="name" 
                    value="{{ $cliente->name }}"
                    required
                    class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                >
            </div>

            <!-- Email -->
            <div>
                <label for="email_{{ $cliente->id }}" class="block text-sm font-semibold text-neutral-700 mb-2">
                    E-mail *
                </label>
                <input 
                    type="email" 
                    id="email_{{ $cliente->id }}" 
                    name="email" 
                    value="{{ $cliente->email }}"
                    required
                    class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                >
            </div>

            <!-- Telefone -->
            <div>
                <label for="telefone_{{ $cliente->id }}" class="block text-sm font-semibold text-neutral-700 mb-2">
                    Telefone
                </label>
                <input 
                    type="text" 
                    id="telefone_{{ $cliente->id }}" 
                    name="telefone" 
                    value="{{ $cliente->telefone }}"
                    class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                >
            </div>

            <!-- Data de Nascimento -->
            <div>
                <label for="data_nascimento_{{ $cliente->id }}" class="block text-sm font-semibold text-neutral-700 mb-2">
                    Data de Nascimento
                </label>
                <input 
                    type="date" 
                    id="data_nascimento_{{ $cliente->id }}" 
                    name="data_nascimento" 
                    value="{{ $cliente->data_nascimento?->format('Y-m-d') }}"
                    class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                >
            </div>

            <div class="flex items-center gap-3 pt-4">
                <button 
                    type="button"
                    onclick="closeModal('editar-cliente-{{ $cliente->id }}')"
                    class="px-4 py-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 rounded-lg font-medium transition-colors"
                >
                    Cancelar
                </button>
                <button 
                    type="submit"
                    class="flex-1 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium transition-colors"
                >
                    Salvar Alterações
                </button>
            </div>
        </form>
    </x-modal>

    <!-- Modal de Deletar Cliente -->
    <x-modal id="deletar-cliente-{{ $cliente->id }}" title="Confirmar Exclusão" size="md">
        <div class="space-y-4">
            <div class="flex items-center gap-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <h4 class="font-bold text-neutral-900 mb-1">Atenção!</h4>
                    <p class="text-sm text-neutral-700">Esta ação não pode ser desfeita.</p>
                </div>
            </div>

            <div class="p-4 bg-neutral-50 rounded-lg">
                <p class="text-sm text-neutral-700 mb-2">Você está prestes a excluir:</p>
                <div class="space-y-1 text-sm">
                    <div><span class="font-semibold">Nome:</span> {{ $cliente->name }}</div>
                    <div><span class="font-semibold">E-mail:</span> {{ $cliente->email }}</div>
                    <div><span class="font-semibold">Cadastrado em:</span> {{ $cliente->created_at->format('d/m/Y') }}</div>
                </div>
            </div>

            <p class="text-sm text-neutral-600">
                Tem certeza que deseja excluir este cliente?
            </p>
        </div>

        <x-slot name="footer">
            <button 
                type="button"
                onclick="closeModal('deletar-cliente-{{ $cliente->id }}')"
                class="px-4 py-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 rounded-lg font-medium transition-colors"
            >
                Cancelar
            </button>
            <form method="POST" action="{{ route('admin.clientes.destroy', $cliente) }}" class="inline">
                @csrf
                @method('DELETE')
                <button 
                    type="submit"
                    class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg font-medium transition-colors inline-flex items-center gap-2"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Sim, Excluir
                </button>
            </form>
        </x-slot>
    </x-modal>
@endforeach
@endsection
