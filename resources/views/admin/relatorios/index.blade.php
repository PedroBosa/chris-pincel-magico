@extends('layouts.admin')

@section('page-title', 'Relatório de Agendamentos')
@section('page-description', 'Análise detalhada dos agendamentos por período')

@section('content')
<div class="space-y-6">
    <!-- Filtros de Período -->
    <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-6">
        <form method="GET" action="{{ route('admin.relatorios.index') }}" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="flex-1">
                <label for="inicio" class="block text-sm font-medium text-neutral-700 mb-2">
                    Data Inicial
                </label>
                <input 
                    type="date" 
                    id="inicio" 
                    name="inicio" 
                    value="{{ $inicio->format('Y-m-d') }}"
                    class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                >
            </div>

            <div class="flex-1">
                <label for="fim" class="block text-sm font-medium text-neutral-700 mb-2">
                    Data Final
                </label>
                <input 
                    type="date" 
                    id="fim" 
                    name="fim" 
                    value="{{ $fim->format('Y-m-d') }}"
                    class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                >
            </div>
            
            <button 
                type="submit"
                class="px-6 py-2.5 bg-primary-500 hover:bg-primary-600 text-white rounded-lg font-medium transition-colors flex items-center gap-2"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <span>Filtrar</span>
            </button>
        </form>
    </div>

    <!-- Cards de Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Total de Agendamentos -->
        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-6">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-neutral-600">Total de Agendamentos</span>
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-bold text-neutral-900">{{ $totalAgendamentos }}</div>
        </div>

        <!-- Faturamento Total -->
        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-6">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-neutral-600">Faturamento Total</span>
                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-bold text-neutral-900">R$ {{ number_format($faturamentoTotal, 2, ',', '.') }}</div>
        </div>

        <!-- Ticket Médio -->
        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-6">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-neutral-600">Ticket Médio</span>
                <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-bold text-neutral-900">R$ {{ number_format($ticketMedio, 2, ',', '.') }}</div>
        </div>

        <!-- Pendentes -->
        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-6">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-neutral-600">Pendentes</span>
                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-bold text-neutral-900">{{ $agendamentosPorStatus->get('PENDENTE', 0) }}</div>
        </div>
    </div>

    <!-- Gráfico de Status -->
    <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-6">
        <h3 class="text-lg font-bold text-neutral-900 mb-6">Distribuição por Status</h3>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @php
                $statusInfo = [
                    'PENDENTE' => ['label' => 'Pendente', 'color' => 'amber', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                    'CONFIRMADO' => ['label' => 'Confirmado', 'color' => 'blue', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                    'CONCLUIDO' => ['label' => 'Concluído', 'color' => 'emerald', 'icon' => 'M5 13l4 4L19 7'],
                    'CANCELADO' => ['label' => 'Cancelado', 'color' => 'red', 'icon' => 'M6 18L18 6M6 6l12 12'],
                ];
            @endphp

            @foreach($statusInfo as $status => $info)
                @php
                    $count = $agendamentosPorStatus->get($status, 0);
                    $percentage = $totalAgendamentos > 0 ? ($count / $totalAgendamentos) * 100 : 0;
                @endphp
                <div class="bg-{{ $info['color'] }}-50 border border-{{ $info['color'] }}-200 rounded-lg p-4">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 bg-{{ $info['color'] }}-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-{{ $info['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $info['icon'] }}" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-{{ $info['color'] }}-900">{{ $count }}</div>
                            <div class="text-xs text-{{ $info['color'] }}-600">{{ $info['label'] }}</div>
                        </div>
                    </div>
                    <div class="w-full bg-{{ $info['color'] }}-200 rounded-full h-2">
                        <div class="bg-{{ $info['color'] }}-500 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                    </div>
                    <div class="text-xs text-{{ $info['color'] }}-600 mt-2 text-right">{{ number_format($percentage, 1) }}%</div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Tabela de Agendamentos -->
    @if($agendamentos->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 overflow-hidden">
            <div class="p-6 border-b border-neutral-200 flex items-center justify-between">
                <h3 class="text-lg font-bold text-neutral-900">Lista de Agendamentos</h3>
                <span class="text-sm text-neutral-600">{{ $agendamentos->count() }} agendamentos encontrados</span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-neutral-50 border-b border-neutral-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-600 uppercase tracking-wider">Data/Hora</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-600 uppercase tracking-wider">Cliente</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-600 uppercase tracking-wider">Serviço</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-600 uppercase tracking-wider">Valor</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100">
                        @foreach($agendamentos as $agendamento)
                            <tr class="hover:bg-neutral-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900">
                                    <div class="font-medium">{{ $agendamento->data_hora_inicio->format('d/m/Y') }}</div>
                                    <div class="text-neutral-500">{{ $agendamento->data_hora_inicio->format('H:i') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-primary-500 to-primary-400 flex items-center justify-center flex-shrink-0">
                                            <span class="text-white font-bold text-xs">
                                                {{ strtoupper(substr($agendamento->cliente->name ?? 'C', 0, 2)) }}
                                            </span>
                                        </div>
                                        <div class="text-sm font-medium text-neutral-900">
                                            {{ $agendamento->cliente->name ?? 'N/A' }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-neutral-900">
                                    {{ $agendamento->servico->nome ?? 'N/A' }}
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
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold border {{ $statusColors[$agendamento->status] ?? 'bg-neutral-100 text-neutral-700 border-neutral-200' }}">
                                        {{ $agendamento->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-neutral-900">
                                    R$ {{ number_format($agendamento->valor_total, 2, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-12 text-center">
            <div class="w-16 h-16 bg-neutral-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-neutral-900 mb-2">
                Nenhum agendamento encontrado
            </h3>
            <p class="text-neutral-600">
                Não há agendamentos para o período selecionado
            </p>
        </div>
    @endif
</div>
@endsection
