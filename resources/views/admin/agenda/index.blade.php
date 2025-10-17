@extends('layouts.admin')

@section('page-title', 'Agenda')
@section('page-description', 'Visualize e gerencie os agendamentos do dia')

@section('content')
<div class="space-y-6">
    <!-- Filtro de Data -->
    <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-6">
        <form method="GET" action="{{ route('admin.agenda.index') }}" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="flex-1">
                <label for="data" class="block text-sm font-medium text-neutral-700 mb-2">
                    Selecionar Data
                </label>
                <input 
                    type="date" 
                    id="data" 
                    name="data" 
                    value="{{ $data->format('Y-m-d') }}"
                    class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                >
            </div>
            
            <div class="flex gap-2">
                <button 
                    type="submit"
                    class="px-6 py-2.5 bg-primary-500 hover:bg-primary-600 text-white rounded-lg font-medium transition-colors flex items-center gap-2"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <span>Buscar</span>
                </button>
                
                <a 
                    href="{{ route('admin.agenda.index') }}"
                    class="px-6 py-2.5 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 rounded-lg font-medium transition-colors"
                >
                    Hoje
                </a>
            </div>
        </form>
    </div>

    <!-- Header com Data Selecionada -->
    <div class="bg-gradient-to-br from-primary-500 to-primary-400 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold mb-1">
                    {{ $data->format('d/m/Y') }}
                </h2>
                <p class="text-primary-100">
                    {{ ucfirst($data->locale('pt_BR')->isoFormat('dddd')) }}
                </p>
            </div>
            <div class="text-right">
                <div class="text-3xl font-bold">{{ $agendamentos->count() }}</div>
                <div class="text-sm text-primary-100">{{ $agendamentos->count() === 1 ? 'agendamento' : 'agendamentos' }}</div>
            </div>
        </div>
    </div>

    <!-- Timeline de Agendamentos -->
    @if($agendamentos->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 overflow-hidden">
            <div class="p-6 border-b border-neutral-200">
                <h3 class="text-lg font-bold text-neutral-900">Agenda do Dia</h3>
            </div>
            
            <div class="divide-y divide-neutral-100">
                @foreach($agendamentos as $agendamento)
                    <div class="p-6 hover:bg-neutral-50 transition-colors">
                        <div class="flex items-start gap-4">
                            <!-- Horário -->
                            <div class="flex-shrink-0 w-24">
                                <div class="text-lg font-bold text-neutral-900">
                                    {{ $agendamento->data_hora_inicio->format('H:i') }}
                                </div>
                                <div class="text-sm text-neutral-500">
                                    {{ $agendamento->data_hora_fim ? $agendamento->data_hora_fim->format('H:i') : '-' }}
                                </div>
                            </div>

                            <!-- Conteúdo -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-4 mb-3">
                                    <div class="flex items-center gap-3">
                                        <!-- Avatar Cliente -->
                                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-primary-500 to-primary-400 flex items-center justify-center flex-shrink-0">
                                            <span class="text-white font-bold text-sm">
                                                {{ strtoupper(substr($agendamento->cliente->name ?? 'C', 0, 2)) }}
                                            </span>
                                        </div>
                                        
                                        <div>
                                            <h4 class="font-semibold text-neutral-900">
                                                {{ $agendamento->cliente->name ?? 'Cliente não encontrado' }}
                                            </h4>
                                            <p class="text-sm text-neutral-600">
                                                {{ $agendamento->cliente->email ?? '-' }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Status -->
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

                                <!-- Serviço -->
                                <div class="flex items-center gap-2 text-sm text-neutral-600 mb-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                    </svg>
                                    <span class="font-medium">{{ $agendamento->servico->nome ?? 'Serviço não encontrado' }}</span>
                                    <span class="text-neutral-400">•</span>
                                    <span>R$ {{ number_format($agendamento->valor_total, 2, ',', '.') }}</span>
                                </div>

                                <!-- Observações -->
                                @if($agendamento->observacoes)
                                    <div class="flex items-start gap-2 text-sm text-neutral-600 bg-neutral-50 rounded-lg p-3 mt-3">
                                        <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                        </svg>
                                        <span>{{ $agendamento->observacoes }}</span>
                                    </div>
                                @endif

                                <!-- Ações -->
                                <div class="flex gap-2 mt-4">
                                    <button 
                                        type="button"
                                        onclick="openModal('agenda-{{ $agendamento->id }}')"
                                        class="px-4 py-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 rounded-lg text-sm font-medium transition-colors inline-flex items-center gap-2"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Ver Detalhes
                                    </button>
                                    
                                    @if($agendamento->status === 'PENDENTE')
                                        <form method="POST" action="{{ route('admin.agendamentos.status', $agendamento) }}" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="CONFIRMADO">
                                            <button 
                                                type="submit"
                                                class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg text-sm font-medium transition-colors"
                                            >
                                                Confirmar
                                            </button>
                                        </form>
                                    @endif

                                    @if(in_array($agendamento->status, ['PENDENTE', 'CONFIRMADO']))
                                        <form method="POST" action="{{ route('admin.agendamentos.status', $agendamento) }}" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="CONCLUIDO">
                                            <button 
                                                type="submit"
                                                class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg text-sm font-medium transition-colors"
                                            >
                                                Concluir
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal para cada agendamento da agenda -->
                    <x-modal id="agenda-{{ $agendamento->id }}" title="Detalhes do Agendamento" size="3xl">
                        <div class="space-y-6">
                            <!-- Header com Status e Horário -->
                            <div class="p-6 bg-gradient-to-br from-primary-50 to-white rounded-xl border border-primary-100">
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <div class="text-sm text-neutral-600 mb-1">Status</div>
                                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold border {{ $statusColors[$agendamento->status] ?? 'bg-neutral-100 text-neutral-700 border-neutral-200' }}">
                                            {{ $agendamento->status }}
                                        </span>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm text-neutral-600 mb-1">ID do Agendamento</div>
                                        <div class="text-2xl font-bold text-neutral-900">#{{ $agendamento->id }}</div>
                                    </div>
                                </div>
                                <div class="grid grid-cols-3 gap-4 pt-4 border-t border-primary-100">
                                    <div>
                                        <div class="text-xs text-neutral-600 mb-1">Data</div>
                                        <div class="text-lg font-bold text-neutral-900">{{ $agendamento->data_hora_inicio->format('d/m/Y') }}</div>
                                    </div>
                                    <div>
                                        <div class="text-xs text-neutral-600 mb-1">Início</div>
                                        <div class="text-lg font-bold text-primary-600">{{ $agendamento->data_hora_inicio->format('H:i') }}</div>
                                    </div>
                                    <div>
                                        <div class="text-xs text-neutral-600 mb-1">Término</div>
                                        <div class="text-lg font-bold text-primary-600">{{ $agendamento->data_hora_fim->format('H:i') }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Cliente -->
                            <div class="p-4 bg-neutral-50 rounded-xl">
                                <h5 class="font-bold text-neutral-900 mb-3 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Cliente
                                </h5>
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-primary-500 to-primary-400 flex items-center justify-center shadow-lg">
                                        <span class="text-white font-bold text-2xl">{{ strtoupper(substr($agendamento->cliente->name ?? 'C', 0, 2)) }}</span>
                                    </div>
                                    <div class="flex-1">
                                        <div class="font-bold text-neutral-900 mb-1">{{ $agendamento->cliente->name ?? 'Cliente não encontrado' }}</div>
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

                            <!-- Serviço -->
                            <div class="p-4 bg-neutral-50 rounded-xl">
                                <h5 class="font-bold text-neutral-900 mb-3 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                    </svg>
                                    Serviço
                                </h5>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <div class="text-sm text-neutral-600 mb-1">Nome</div>
                                        <div class="font-semibold text-neutral-900">{{ $agendamento->servico->nome ?? 'Serviço não encontrado' }}</div>
                                    </div>
                                    <div>
                                        <div class="text-sm text-neutral-600 mb-1">Duração</div>
                                        <div class="font-semibold text-neutral-900">{{ $agendamento->servico->duracao_minutos ?? 0 }} minutos</div>
                                    </div>
                                </div>
                                @if($agendamento->servico && $agendamento->servico->descricao)
                                    <div class="mt-3 pt-3 border-t border-neutral-200">
                                        <p class="text-sm text-neutral-600">{{ $agendamento->servico->descricao }}</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Valor -->
                            <div class="p-4 bg-primary-50 rounded-xl border border-primary-100">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="text-sm font-semibold text-neutral-600">Valor Total</div>
                                        <div class="text-xs text-neutral-500">Serviço completo</div>
                                    </div>
                                    <div class="text-3xl font-bold text-primary-600">R$ {{ number_format($agendamento->valor_total, 2, ',', '.') }}</div>
                                </div>
                            </div>

                            <!-- Observações -->
                            @if($agendamento->observacoes)
                                <div class="p-4 bg-amber-50 rounded-xl border border-amber-200">
                                    <h5 class="font-bold text-neutral-900 mb-2 flex items-center gap-2">
                                        <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                        </svg>
                                        Observações
                                    </h5>
                                    <p class="text-sm text-neutral-700">{{ $agendamento->observacoes }}</p>
                                </div>
                            @endif

                            <!-- Metadados -->
                            <div class="p-4 bg-neutral-50 rounded-xl text-xs text-neutral-500">
                                <div class="flex justify-between mb-1">
                                    <span>Agendado em:</span>
                                    <span class="font-medium">{{ $agendamento->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                @if($agendamento->updated_at->ne($agendamento->created_at))
                                    <div class="flex justify-between">
                                        <span>Última atualização:</span>
                                        <span class="font-medium">{{ $agendamento->updated_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <x-slot name="footer">
                            <button 
                                type="button"
                                onclick="closeModal('agenda-{{ $agendamento->id }}')"
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

                            @if(in_array($agendamento->status, ['PENDENTE', 'CONFIRMADO']))
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
                @endforeach
            </div>
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-12 text-center">
            <div class="w-16 h-16 bg-neutral-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-neutral-900 mb-2">
                Nenhum agendamento para esta data
            </h3>
            <p class="text-neutral-600 mb-6">
                Não há agendamentos para {{ $data->format('d/m/Y') }}
            </p>
            <a 
                href="{{ route('admin.agendamentos.index') }}"
                class="inline-flex items-center gap-2 px-6 py-3 bg-primary-500 hover:bg-primary-600 text-white rounded-lg font-medium transition-colors"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <span>Ver Todos Agendamentos</span>
            </a>
        </div>
    @endif
</div>
@endsection
