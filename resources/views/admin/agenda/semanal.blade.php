@extends('layouts.admin')

@section('page-title', 'Agenda Semanal')
@section('page-description', 'Visualização em grade dos agendamentos')

@section('content')
<div class="space-y-6">
    <!-- Navegação de Semana -->
    <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-neutral-900 mb-1">
                    {{ $inicioSemana->format('d/m') }} - {{ $fimSemana->format('d/m/Y') }}
                </h2>
                <p class="text-sm text-neutral-600">
                    Semana {{ $inicioSemana->weekOfYear }} de {{ $inicioSemana->year }}
                </p>
            </div>
            
            <div class="flex gap-2">
                <a href="{{ route('admin.agenda.semanal', ['data' => $inicioSemana->copy()->subWeek()->format('Y-m-d')]) }}" 
                   class="px-4 py-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 rounded-lg font-medium transition-colors inline-flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Anterior
                </a>
                
                <a href="{{ route('admin.agenda.semanal') }}"
                   class="px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg font-medium transition-colors">
                    Hoje
                </a>
                
                <a href="{{ route('admin.agenda.semanal', ['data' => $inicioSemana->copy()->addWeek()->format('Y-m-d')]) }}"
                   class="px-4 py-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 rounded-lg font-medium transition-colors inline-flex items-center gap-2">
                    Próxima
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Grid da Agenda Semanal -->
    <div class="bg-white rounded-xl shadow-sm border border-neutral-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[1200px] table-fixed">
                <!-- Cabeçalho dos Dias -->
                <thead>
                    <tr class="border-b border-neutral-200 bg-neutral-50">
                        <th class="w-24 p-4 text-sm font-semibold text-neutral-600 border-r border-neutral-200 text-left">
                            Horário
                        </th>
                        @foreach($diasSemana as $dia)
                            <th class="p-4 text-center border-r border-neutral-200 last:border-r-0">
                                <div class="text-xs font-semibold text-neutral-600 uppercase">
                                    {{ $dia->locale('pt_BR')->isoFormat('ddd') }}
                                </div>
                                <div class="text-2xl font-bold {{ $dia->isToday() ? 'text-primary-600' : 'text-neutral-900' }} mt-1">
                                    {{ $dia->format('d') }}
                                </div>
                                <div class="text-xs text-neutral-500">
                                    {{ $dia->locale('pt_BR')->isoFormat('MMM') }}
                                </div>
                            </th>
                        @endforeach
                    </tr>
                </thead>

                <!-- Grade de Horários -->
                <tbody>
                    @php
                        $horaInicio = 9;
                        $horaFim = 19;
                    @endphp

                    @for($hora = $horaInicio; $hora < $horaFim; $hora++)
                        <tr class="border-b border-neutral-100">
                            <!-- Coluna de Horário -->
                            <td class="p-2 text-right text-sm font-medium text-neutral-600 bg-neutral-50 border-r border-neutral-200 align-top">
                                {{ sprintf('%02d:00', $hora) }}
                            </td>

                            <!-- Colunas dos Dias -->
                            @foreach($diasSemana as $diaIndex => $dia)
                                <td class="relative min-h-[80px] h-20 border-r border-neutral-100 last:border-r-0 p-1 align-top {{ $dia->isToday() ? 'bg-primary-50/30' : '' }}">
                                    @php
                                        $horaStr = sprintf('%02d:00', $hora);
                                        $agendamentosHora = collect($agendamentos[$diaIndex] ?? [])->filter(function($agendamento) use ($horaStr) {
                                            return $agendamento->data_hora_inicio->format('H:00') === $horaStr;
                                        });
                                    @endphp

                                    @foreach($agendamentosHora as $agendamento)
                                        @php
                                            $duracao = $agendamento->data_hora_inicio->diffInMinutes($agendamento->data_hora_fim);
                                            $altura = ($duracao / 60) * 80;
                                            
                                            $statusColors = [
                                                'PENDENTE' => 'bg-amber-100 border-amber-300 text-amber-900',
                                                'CONFIRMADO' => 'bg-blue-100 border-blue-300 text-blue-900',
                                                'CONCLUIDO' => 'bg-emerald-100 border-emerald-300 text-emerald-900',
                                                'CANCELADO' => 'bg-red-100 border-red-300 text-red-900',
                                            ];
                                        @endphp

                                        <div 
                                            class="absolute left-1 right-1 top-1 rounded-lg border-l-4 p-2 flex flex-col items-center justify-center text-center {{ $statusColors[$agendamento->status] ?? 'bg-neutral-100 border-neutral-300' }} cursor-pointer hover:shadow-md transition-shadow overflow-hidden"
                                            style="height: {{ $altura }}px; min-height: 60px;"
                                            onclick="openModal('agendamento-{{ $agendamento->id }}')"
                                            title="{{ $agendamento->cliente->name ?? 'N/A' }} - {{ $agendamento->servico->nome ?? 'N/A' }}"
                                        >
                                            <div class="text-[10px] font-bold leading-tight mb-1 whitespace-nowrap">
                                                {{ $agendamento->data_hora_inicio->format('H:i') }} - {{ $agendamento->data_hora_fim->format('H:i') }}
                                            </div>
                                            <div class="text-xs font-semibold leading-tight overflow-hidden text-ellipsis line-clamp-2 w-full px-1">
                                                {{ $agendamento->cliente->name ?? 'Cliente' }}
                                            </div>
                                        </div>
                                    @endforeach
                                </td>
                            @endforeach
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>

    <!-- Legenda -->
    <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-6">
        <h3 class="text-sm font-bold text-neutral-900 mb-4">Legenda de Status</h3>
        <div class="flex flex-wrap gap-4">
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 rounded border-l-4 bg-amber-100 border-amber-300"></div>
                <span class="text-sm text-neutral-700">Pendente</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 rounded border-l-4 bg-blue-100 border-blue-300"></div>
                <span class="text-sm text-neutral-700">Confirmado</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 rounded border-l-4 bg-emerald-100 border-emerald-300"></div>
                <span class="text-sm text-neutral-700">Concluído</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 rounded border-l-4 bg-red-100 border-red-300"></div>
                <span class="text-sm text-neutral-700">Cancelado</span>
            </div>
        </div>
    </div>
</div>

<!-- Modais de Detalhes dos Agendamentos -->
@foreach($diasSemana as $diaAgendamentos)
    @foreach($agendamentos[$loop->index] ?? [] as $agendamento)
        <x-modal name="agendamento-{{ $agendamento->id }}" maxWidth="2xl">
            <x-slot name="title">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-primary-500 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-neutral-900">Agendamento #{{ $agendamento->id }}</h3>
                        <p class="text-sm text-neutral-600">{{ $agendamento->data_hora_inicio->format('d/m/Y') }} às {{ $agendamento->data_hora_inicio->format('H:i') }}</p>
                    </div>
                </div>
            </x-slot>

            <div class="space-y-4">
                <!-- Cliente -->
                <div class="p-4 bg-neutral-50 rounded-lg">
                    <div class="text-sm font-semibold text-neutral-700 mb-2">Cliente</div>
                    <div class="font-bold text-neutral-900">{{ $agendamento->cliente->name ?? 'N/A' }}</div>
                    <div class="text-sm text-neutral-600">{{ $agendamento->cliente->email ?? 'N/A' }}</div>
                </div>

                <!-- Serviço -->
                <div class="p-4 bg-neutral-50 rounded-lg">
                    <div class="text-sm font-semibold text-neutral-700 mb-2">Serviço</div>
                    <div class="font-bold text-neutral-900">{{ $agendamento->servico->nome ?? 'N/A' }}</div>
                    <div class="text-sm text-neutral-600">Duração: {{ $agendamento->servico->duracao_minutos ?? 0 }} minutos</div>
                    <div class="text-sm text-neutral-600">Valor: R$ {{ number_format($agendamento->valor_total, 2, ',', '.') }}</div>
                </div>

                <!-- Observações -->
                @if($agendamento->observacoes)
                    <div class="p-4 bg-amber-50 rounded-lg">
                        <div class="text-sm font-semibold text-neutral-700 mb-2">Observações</div>
                        <p class="text-sm text-neutral-700">{{ $agendamento->observacoes }}</p>
                    </div>
                @endif

                <!-- Status -->
                <div class="p-4 bg-primary-50 rounded-lg">
                    <div class="text-sm font-semibold text-neutral-700 mb-2">Status</div>
                    <span class="inline-block px-3 py-1 rounded-full text-sm font-bold {{ $statusColors[$agendamento->status] ?? 'bg-neutral-100 text-neutral-700' }}">
                        {{ $agendamento->status }}
                    </span>
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

                <a href="{{ route('admin.agendamentos.show', $agendamento) }}" class="px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg font-medium transition-colors">
                    Ver Detalhes
                </a>
            </x-slot>
        </x-modal>
    @endforeach
@endforeach
@endsection
