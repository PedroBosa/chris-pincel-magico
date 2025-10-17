@extends('layouts.admin')

@section('title', 'Agendamentos')
@section('page-title', 'Agendamentos')
@section('page-description', 'Gerencie todos os agendamentos do sistema')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div class="flex items-center gap-4">
        <div class="relative">
            <input 
                type="search" 
                placeholder="Buscar agendamento..." 
                class="pl-10 pr-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
            >
            <svg class="w-5 h-5 text-neutral-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
        
        <select class="px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
            <option value="">Todos os status</option>
            <option value="pendente">Pendente</option>
            <option value="confirmado">Confirmado</option>
            <option value="concluido">Concluído</option>
            <option value="cancelado">Cancelado</option>
        </select>
    </div>

    <a href="#" onclick="openModal('novo-agendamento'); return false;" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-primary-500 to-primary-400 text-white rounded-lg font-semibold hover:shadow-lg transition-all">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
        <span>Novo Agendamento</span>
    </a>
</div>

<div class="bg-white rounded-2xl border border-neutral-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-neutral-50 border-b border-neutral-200">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-neutral-600 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-neutral-600 uppercase tracking-wider">Cliente</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-neutral-600 uppercase tracking-wider">Serviço</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-neutral-600 uppercase tracking-wider">Data/Hora</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-neutral-600 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-neutral-600 uppercase tracking-wider">Valor</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-neutral-600 uppercase tracking-wider">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200">
                @forelse ($agendamentos as $agendamento)
                    <tr class="hover:bg-neutral-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-semibold text-neutral-900">#{{ $agendamento->id }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary-500 to-primary-400 flex items-center justify-center flex-shrink-0">
                                    <span class="text-white font-bold text-sm">{{ strtoupper(substr($agendamento->cliente->name ?? 'C', 0, 2)) }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-neutral-900">{{ $agendamento->cliente->name ?? 'Cliente removido' }}</p>
                                    <p class="text-xs text-neutral-500">{{ $agendamento->cliente->email ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-medium text-neutral-900">{{ $agendamento->servico->nome ?? 'Serviço removido' }}</p>
                            <p class="text-xs text-neutral-500">{{ $agendamento->servico->duracao_minutos ?? 0 }} min</p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="text-sm text-neutral-900">{{ $agendamento->data_hora_inicio->format('d/m/Y') }}</p>
                            <p class="text-xs text-neutral-500">{{ $agendamento->data_hora_inicio->format('H:i') }}</p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'PENDENTE' => 'bg-amber-100 text-amber-700 border-amber-200',
                                    'CONFIRMADO' => 'bg-blue-100 text-blue-700 border-blue-200',
                                    'CONCLUIDO' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                    'CANCELADO' => 'bg-red-100 text-red-700 border-red-200',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border {{ $statusColors[$agendamento->status] ?? 'bg-neutral-100 text-neutral-700 border-neutral-200' }}">
                                {{ $agendamento->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-semibold text-neutral-900">R$ {{ number_format($agendamento->valor_total, 2, ',', '.') }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end gap-2">
                                <!-- Ver Detalhes -->
                                <button 
                                    type="button"
                                    onclick="openModal('agendamento-{{ $agendamento->id }}')"
                                    class="p-2 text-primary-600 hover:text-primary-700 hover:bg-primary-50 rounded-lg transition-colors"
                                    title="Ver Detalhes"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>

                                <!-- Editar -->
                                <button 
                                    type="button"
                                    onclick="openModal('editar-agendamento-{{ $agendamento->id }}')"
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
                                    onclick="openModal('deletar-agendamento-{{ $agendamento->id }}')"
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

                    <!-- Modal para cada agendamento -->
                    <x-modal id="agendamento-{{ $agendamento->id }}" title="Detalhes do Agendamento #{{ $agendamento->id }}" size="3xl">
                        <div class="space-y-6">
                            <!-- Status Badge -->
                            <div class="flex items-center justify-between p-4 bg-gradient-to-br from-neutral-50 to-white rounded-xl border border-neutral-200">
                                <div>
                                    <div class="text-sm text-neutral-600 mb-1">Status do Agendamento</div>
                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold border {{ $statusColors[$agendamento->status] ?? 'bg-neutral-100 text-neutral-700 border-neutral-200' }}">
                                        {{ $agendamento->status }}
                                    </span>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm text-neutral-600 mb-1">Valor Total</div>
                                    <div class="text-2xl font-bold text-primary-600">R$ {{ number_format($agendamento->valor_total, 2, ',', '.') }}</div>
                                </div>
                            </div>

                            <!-- Cliente Info -->
                            <div class="p-4 bg-neutral-50 rounded-xl">
                                <h5 class="font-bold text-neutral-900 mb-3 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Informações do Cliente
                                </h5>
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-primary-500 to-primary-400 flex items-center justify-center shadow-lg">
                                        <span class="text-white font-bold text-2xl">{{ strtoupper(substr($agendamento->cliente->name ?? 'C', 0, 2)) }}</span>
                                    </div>
                                    <div class="flex-1">
                                        <div class="font-bold text-neutral-900 mb-1">{{ $agendamento->cliente->name ?? 'Cliente removido' }}</div>
                                        <div class="text-sm text-neutral-600">{{ $agendamento->cliente->email ?? '-' }}</div>
                                        @if($agendamento->cliente && $agendamento->cliente->telefone)
                                            <div class="text-sm text-neutral-600">{{ $agendamento->cliente->telefone }}</div>
                                        @endif
                                    </div>
                                    @if($agendamento->cliente)
                                        <a href="mailto:{{ $agendamento->cliente->email }}" class="px-3 py-2 bg-white border border-neutral-300 hover:bg-neutral-50 text-neutral-700 rounded-lg text-sm font-medium transition-colors">
                                            Contatar
                                        </a>
                                    @endif
                                </div>
                            </div>

                            <!-- Serviço Info -->
                            <div class="p-4 bg-neutral-50 rounded-xl">
                                <h5 class="font-bold text-neutral-900 mb-3 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                    </svg>
                                    Serviço Agendado
                                </h5>
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-neutral-600">Nome:</span>
                                        <span class="font-semibold text-neutral-900">{{ $agendamento->servico->nome ?? 'Serviço removido' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-neutral-600">Duração:</span>
                                        <span class="font-semibold text-neutral-900">{{ $agendamento->servico->duracao_minutos ?? 0 }} minutos</span>
                                    </div>
                                    @if($agendamento->servico && $agendamento->servico->descricao)
                                        <div class="pt-2 border-t border-neutral-200">
                                            <p class="text-sm text-neutral-600">{{ $agendamento->servico->descricao }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Data e Hora -->
                            <div class="grid grid-cols-2 gap-4">
                                <div class="p-4 bg-neutral-50 rounded-xl">
                                    <div class="text-xs font-semibold text-neutral-600 uppercase tracking-wider mb-2">Data e Hora de Início</div>
                                    <div class="text-lg font-bold text-neutral-900">{{ $agendamento->data_hora_inicio->format('d/m/Y') }}</div>
                                    <div class="text-2xl font-bold text-primary-600">{{ $agendamento->data_hora_inicio->format('H:i') }}</div>
                                </div>
                                <div class="p-4 bg-neutral-50 rounded-xl">
                                    <div class="text-xs font-semibold text-neutral-600 uppercase tracking-wider mb-2">Data e Hora de Término</div>
                                    <div class="text-lg font-bold text-neutral-900">{{ $agendamento->data_hora_fim->format('d/m/Y') }}</div>
                                    <div class="text-2xl font-bold text-primary-600">{{ $agendamento->data_hora_fim->format('H:i') }}</div>
                                </div>
                            </div>

                            <!-- Observações -->
                            @if($agendamento->observacoes)
                                <div class="p-4 bg-amber-50 rounded-xl border border-amber-200">
                                    <h5 class="font-bold text-neutral-900 mb-2 flex items-center gap-2">
                                        <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                        </svg>
                                        Observações do Cliente
                                    </h5>
                                    <p class="text-sm text-neutral-700">{{ $agendamento->observacoes }}</p>
                                </div>
                            @endif

                            <!-- Metadados -->
                            <div class="p-4 bg-neutral-50 rounded-xl text-xs text-neutral-500">
                                <div class="flex justify-between mb-1">
                                    <span>Criado em:</span>
                                    <span class="font-medium">{{ $agendamento->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                @if($agendamento->updated_at->ne($agendamento->created_at))
                                    <div class="flex justify-between">
                                        <span>Atualizado em:</span>
                                        <span class="font-medium">{{ $agendamento->updated_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <x-slot name="footer">
                            <button 
                                type="button"
                                onclick="closeModal('agendamento-{{ $agendamento->id }}')"
                                class="px-4 py-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 rounded-lg font-medium transition-colors"
                            >
                                Fechar
                            </button>
                            
                            @if($agendamento->status === 'PENDENTE')
                                <form method="POST" action="{{ route('admin.agendamentos.status', $agendamento) }}" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="CONFIRMADO">
                                    <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium transition-colors inline-flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Confirmar
                                    </button>
                                </form>
                            @endif

                            @if($agendamento->status === 'CONFIRMADO')
                                <form method="POST" action="{{ route('admin.agendamentos.status', $agendamento) }}" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="CONCLUIDO">
                                    <button type="submit" class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg font-medium transition-colors inline-flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Concluir
                                    </button>
                                </form>
                            @endif

                            @if(in_array($agendamento->status, ['PENDENTE', 'CONFIRMADO']))
                                <form method="POST" action="{{ route('admin.agendamentos.status', $agendamento) }}" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="CANCELADO">
                                    <button type="submit" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg font-medium transition-colors inline-flex items-center gap-2" onclick="return confirm('Tem certeza que deseja cancelar este agendamento?')">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Cancelar
                                    </button>
                                </form>
                            @endif
                        </x-slot>
                    </x-modal>

                    <!-- Modal de Editar -->
                    <x-modal id="editar-agendamento-{{ $agendamento->id }}" title="Editar Agendamento #{{ $agendamento->id }}" size="2xl">
                        <form method="POST" action="{{ route('admin.agendamentos.update', $agendamento) }}" class="space-y-4" onsubmit="return combineEditDateTime{{ $agendamento->id }}()">
                            @csrf
                            @method('PUT')

                            <input type="hidden" name="cliente_id" value="{{ $agendamento->user_id }}">
                            <input type="hidden" name="servico_id" value="{{ $agendamento->servico_id }}">
                            <input type="hidden" name="data_hora_inicio" id="data_hora_inicio_edit_{{ $agendamento->id }}">

                            <!-- Info do Cliente e Serviço (readonly) -->
                            <div class="p-3 bg-neutral-50 rounded-lg text-sm">
                                <div class="font-semibold text-neutral-700 mb-1">
                                    Cliente: <span class="text-neutral-900">{{ $agendamento->cliente->name ?? 'N/A' }}</span>
                                </div>
                                <div class="font-semibold text-neutral-700">
                                    Serviço: <span class="text-neutral-900">{{ $agendamento->servico->nome ?? 'N/A' }}</span>
                                </div>
                            </div>

                            <!-- Data e Hora -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="data_edit_{{ $agendamento->id }}" class="block text-sm font-semibold text-neutral-700 mb-2">
                                        Data *
                                    </label>
                                    <input 
                                        type="date" 
                                        id="data_edit_{{ $agendamento->id }}" 
                                        value="{{ $agendamento->data_hora_inicio->format('Y-m-d') }}"
                                        required
                                        min="{{ date('Y-m-d') }}"
                                        class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                    >
                                </div>
                                <div>
                                    <label for="hora_edit_{{ $agendamento->id }}" class="block text-sm font-semibold text-neutral-700 mb-2">
                                        Hora *
                                    </label>
                                    <input 
                                        type="time" 
                                        id="hora_edit_{{ $agendamento->id }}" 
                                        value="{{ $agendamento->data_hora_inicio->format('H:i') }}"
                                        required
                                        class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                    >
                                </div>
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status_{{ $agendamento->id }}" class="block text-sm font-semibold text-neutral-700 mb-2">
                                    Status *
                                </label>
                                <select 
                                    id="status_{{ $agendamento->id }}" 
                                    name="status" 
                                    required
                                    class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                >
                                    <option value="PENDENTE" {{ $agendamento->status === 'PENDENTE' ? 'selected' : '' }}>Pendente</option>
                                    <option value="CONFIRMADO" {{ $agendamento->status === 'CONFIRMADO' ? 'selected' : '' }}>Confirmado</option>
                                    <option value="CONCLUIDO" {{ $agendamento->status === 'CONCLUIDO' ? 'selected' : '' }}>Concluído</option>
                                    <option value="CANCELADO" {{ $agendamento->status === 'CANCELADO' ? 'selected' : '' }}>Cancelado</option>
                                    <option value="NO_SHOW" {{ $agendamento->status === 'NO_SHOW' ? 'selected' : '' }}>Não Compareceu</option>
                                </select>
                            </div>

                            <!-- Observações -->
                            <div>
                                <label for="observacoes_{{ $agendamento->id }}" class="block text-sm font-semibold text-neutral-700 mb-2">
                                    Observações
                                </label>
                                <textarea 
                                    id="observacoes_{{ $agendamento->id }}" 
                                    name="observacoes" 
                                    rows="3"
                                    class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                >{{ $agendamento->observacoes }}</textarea>
                            </div>

                            <div class="flex items-center gap-3 pt-4">
                                <button 
                                    type="button"
                                    onclick="closeModal('editar-agendamento-{{ $agendamento->id }}')"
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

                        <script>
                        function combineEditDateTime{{ $agendamento->id }}() {
                            const data = document.getElementById('data_edit_{{ $agendamento->id }}').value;
                            const hora = document.getElementById('hora_edit_{{ $agendamento->id }}').value;
                            
                            if (data && hora) {
                                document.getElementById('data_hora_inicio_edit_{{ $agendamento->id }}').value = `${data} ${hora}:00`;
                                return true;
                            }
                            
                            alert('Por favor, preencha data e hora');
                            return false;
                        }
                        </script>
                    </x-modal>

                    <!-- Modal de Deletar -->
                    <x-modal id="deletar-agendamento-{{ $agendamento->id }}" title="Confirmar Exclusão" size="md">
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
                                    <div><span class="font-semibold">Agendamento:</span> #{{ $agendamento->id }}</div>
                                    <div><span class="font-semibold">Cliente:</span> {{ $agendamento->cliente->name ?? 'N/A' }}</div>
                                    <div><span class="font-semibold">Data:</span> {{ $agendamento->data_hora_inicio->format('d/m/Y H:i') }}</div>
                                </div>
                            </div>

                            <p class="text-sm text-neutral-600">
                                Tem certeza que deseja excluir este agendamento?
                            </p>
                        </div>

                        <x-slot name="footer">
                            <button 
                                type="button"
                                onclick="closeModal('deletar-agendamento-{{ $agendamento->id }}')"
                                class="px-4 py-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 rounded-lg font-medium transition-colors"
                            >
                                Cancelar
                            </button>
                            <form method="POST" action="{{ route('admin.agendamentos.destroy', $agendamento) }}" class="inline">
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
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-neutral-100 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <p class="text-neutral-600 font-medium">Nenhum agendamento encontrado</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($agendamentos->hasPages())
        <div class="px-6 py-4 border-t border-neutral-200">
            {{ $agendamentos->links() }}
        </div>
    @endif
</div>

<!-- Modal Novo Agendamento -->
<x-modal name="novo-agendamento" maxWidth="2xl">
    <x-slot name="title">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-primary-500 flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-neutral-900">Novo Agendamento</h3>
                <p class="text-sm text-neutral-600">Crie um novo agendamento para um cliente</p>
            </div>
        </div>
    </x-slot>

    <form method="POST" action="{{ route('admin.agendamentos.store') }}" class="space-y-6">
        @csrf

        <!-- Cliente -->
        <div>
            <label for="cliente_id" class="block text-sm font-semibold text-neutral-700 mb-2">
                Cliente *
            </label>
            <select 
                name="cliente_id" 
                id="cliente_id" 
                required
                class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
            >
                <option value="">Selecione um cliente</option>
                @foreach($clientes as $cliente)
                    <option value="{{ $cliente->id }}">{{ $cliente->name }} - {{ $cliente->email }}</option>
                @endforeach
            </select>
            @error('cliente_id')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Serviço -->
        <div>
            <label for="servico_id" class="block text-sm font-semibold text-neutral-700 mb-2">
                Serviço *
            </label>
            <select 
                name="servico_id" 
                id="servico_id" 
                required
                onchange="updateServicoInfo(this)"
                class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
            >
                <option value="">Selecione um serviço</option>
                @foreach($servicos as $servico)
                    <option 
                        value="{{ $servico->id }}"
                        data-preco="{{ $servico->preco }}"
                        data-duracao="{{ $servico->duracao_estimada }}"
                    >
                        {{ $servico->nome }} - R$ {{ number_format($servico->preco, 2, ',', '.') }}
                    </option>
                @endforeach
            </select>
            @error('servico_id')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
            <div id="servico-info" class="mt-2 text-sm text-neutral-600 hidden">
                <span id="servico-duracao"></span>
            </div>
        </div>

        <!-- Data e Hora -->
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="data" class="block text-sm font-semibold text-neutral-700 mb-2">
                    Data *
                </label>
                <input 
                    type="date" 
                    name="data" 
                    id="data"
                    required
                    min="{{ date('Y-m-d') }}"
                    class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                >
                @error('data')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="hora" class="block text-sm font-semibold text-neutral-700 mb-2">
                    Hora *
                </label>
                <input 
                    type="time" 
                    name="hora" 
                    id="hora"
                    required
                    class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                >
                @error('hora')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <input type="hidden" name="data_hora_inicio" id="data_hora_inicio">

        <!-- Observações -->
        <div>
            <label for="observacoes" class="block text-sm font-semibold text-neutral-700 mb-2">
                Observações
            </label>
            <textarea 
                name="observacoes" 
                id="observacoes"
                rows="3"
                maxlength="1000"
                class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent resize-none"
                placeholder="Adicione observações sobre o agendamento..."
            ></textarea>
            @error('observacoes')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <x-slot name="footer">
            <button 
                type="button"
                onclick="closeModal('novo-agendamento')"
                class="px-4 py-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 rounded-lg font-medium transition-colors"
            >
                Cancelar
            </button>
            <button 
                type="submit"
                onclick="combineDateTime()"
                class="px-6 py-2 bg-gradient-to-r from-primary-500 to-primary-400 text-white rounded-lg font-semibold hover:shadow-lg transition-all inline-flex items-center gap-2"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Criar Agendamento
            </button>
        </x-slot>
    </form>
</x-modal>

<script>
function updateServicoInfo(select) {
    const option = select.options[select.selectedIndex];
    const preco = option.dataset.preco;
    const duracao = option.dataset.duracao;
    
    const infoDiv = document.getElementById('servico-info');
    const duracaoSpan = document.getElementById('servico-duracao');
    
    if (duracao) {
        duracaoSpan.textContent = `⏱️ Duração estimada: ${duracao} minutos`;
        infoDiv.classList.remove('hidden');
    } else {
        infoDiv.classList.add('hidden');
    }
}

function combineDateTime() {
    const data = document.getElementById('data').value;
    const hora = document.getElementById('hora').value;
    
    if (data && hora) {
        document.getElementById('data_hora_inicio').value = `${data} ${hora}:00`;
    }
}
</script>
@endsection
