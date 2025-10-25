@extends('layouts.admin')

@section('page-title', 'Financeiro')
@section('page-description', 'Controle financeiro e pagamentos')

@section('content')
<div class="space-y-6">
    <!-- Filtros -->
    <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-6">
        <form method="GET" action="{{ route('admin.financeiro.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-2">Mês</label>
                <select name="mes" class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $mes == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-2">Ano</label>
                <select name="ano" class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    @for($y = now()->year; $y >= now()->year - 5; $y--)
                        <option value="{{ $y }}" {{ $ano == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-2">Status</label>
                <select name="status" class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <option value="">Todos</option>
                    <option value="paid" {{ $status == 'paid' ? 'selected' : '' }}>Pago</option>
                    <option value="approved" {{ $status == 'approved' ? 'selected' : '' }}>Aprovado</option>
                    <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pendente</option>
                    <option value="cancelled" {{ $status == 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                    <option value="refunded" {{ $status == 'refunded' ? 'selected' : '' }}>Reembolsado</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg font-medium transition-colors">
                    Filtrar
                </button>
            </div>
        </form>
    </div>

    <!-- Cards de Resumo -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-6">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-neutral-600">Faturamento do Mês</span>
                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-bold text-neutral-900">R$ {{ number_format($totalMes, 2, ',', '.') }}</div>
            <p class="text-xs text-neutral-500 mt-1">{{ \Carbon\Carbon::create()->month((int)$mes)->year((int)$ano)->translatedFormat('F Y') }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-6">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-neutral-600">Total de Pagamentos</span>
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-bold text-neutral-900">{{ $totalPagamentos }}</div>
            <p class="text-xs text-neutral-500 mt-1">Transações no período</p>
        </div>

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
            <p class="text-xs text-neutral-500 mt-1">Valor médio por transação</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-6">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-neutral-600">Pendentes</span>
                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-bold text-neutral-900">{{ $pagamentosPendentes }}</div>
            <p class="text-xs text-neutral-500 mt-1">Aguardando confirmação</p>
        </div>
    </div>

    <!-- Tabela de Pagamentos -->
    @if($pagamentos->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 overflow-hidden">
            <div class="p-6 border-b border-neutral-200">
                <h3 class="text-lg font-bold text-neutral-900">Histórico de Pagamentos</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-neutral-50 border-b border-neutral-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-600 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-600 uppercase tracking-wider">Data</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-600 uppercase tracking-wider">Cliente</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-600 uppercase tracking-wider">Serviço</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-600 uppercase tracking-wider">Método</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-neutral-600 uppercase tracking-wider">Valor</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100">
                        @foreach($pagamentos as $pagamento)
                            <tr class="hover:bg-neutral-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-neutral-600">
                                    #{{ $pagamento->id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900">
                                    {{ $pagamento->created_at->format('d/m/Y') }}
                                    <span class="text-neutral-500 text-xs block">{{ $pagamento->created_at->format('H:i') }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-neutral-900">
                                    {{ $pagamento->agendamento->usuario->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-neutral-600">
                                    {{ $pagamento->agendamento->servico->nome ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-600">
                                    @if($pagamento->forma_pagamento)
                                        <span class="inline-flex items-center gap-1">
                                            @if($pagamento->forma_pagamento == 'credit_card')
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                                </svg>
                                                Cartão de Crédito
                                            @elseif($pagamento->forma_pagamento == 'debit_card')
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                                </svg>
                                                Cartão de Débito
                                            @elseif($pagamento->forma_pagamento == 'pix')
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z"/>
                                                </svg>
                                                PIX
                                            @else
                                                {{ ucfirst($pagamento->forma_pagamento) }}
                                            @endif
                                        </span>
                                    @else
                                        <span class="text-neutral-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'approved' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                            'paid' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                            'pending' => 'bg-amber-100 text-amber-700 border-amber-200',
                                            'cancelled' => 'bg-red-100 text-red-700 border-red-200',
                                            'refunded' => 'bg-purple-100 text-purple-700 border-purple-200',
                                            'due' => 'bg-blue-100 text-blue-700 border-blue-200',
                                            'charged_back' => 'bg-red-100 text-red-700 border-red-200',
                                        ];
                                        $statusLabels = [
                                            'approved' => 'Aprovado',
                                            'paid' => 'Pago',
                                            'pending' => 'Pendente',
                                            'cancelled' => 'Cancelado',
                                            'refunded' => 'Reembolsado',
                                            'due' => 'Em aberto',
                                            'charged_back' => 'Chargeback',
                                        ];
                                        $statusKey = strtolower($pagamento->status);
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold border {{ $statusColors[$statusKey] ?? 'bg-neutral-100 text-neutral-700 border-neutral-200' }}">
                                        {{ $statusLabels[$statusKey] ?? ucfirst(strtolower($pagamento->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="text-sm font-bold text-neutral-900">R$ {{ number_format($pagamento->valor_total, 2, ',', '.') }}</div>
                                    @if($pagamento->valor_capturado && $pagamento->valor_capturado != $pagamento->valor_total)
                                        <div class="text-xs text-emerald-600">Capturado: R$ {{ number_format($pagamento->valor_capturado, 2, ',', '.') }}</div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginação -->
            @if($pagamentos->hasPages())
                <div class="p-6 border-t border-neutral-200">
                    {{ $pagamentos->links() }}
                </div>
            @endif
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-12 text-center">
            <div class="w-16 h-16 bg-neutral-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-neutral-900 mb-2">
                Nenhum pagamento registrado
            </h3>
            <p class="text-neutral-600">
                Os pagamentos aparecerão aqui quando forem realizados
            </p>
        </div>
    @endif
</div>
@endsection
