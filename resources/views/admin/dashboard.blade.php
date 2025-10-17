@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-description', 'Visão geral do seu negócio')

@section('content')
<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Agendamentos Pendentes -->
    <div class="bg-white rounded-2xl p-6 border border-neutral-200 shadow-sm hover:shadow-lg transition-shadow">
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <span class="px-2 py-1 bg-amber-100 text-amber-700 text-xs font-bold rounded-full">Pendentes</span>
        </div>
        <p class="text-3xl font-bold text-neutral-900 mb-1">{{ $agendamentosPendentes }}</p>
        <p class="text-sm text-neutral-600">Aguardando confirmação</p>
    </div>

    <!-- Faturamento Mensal -->
    <div class="bg-white rounded-2xl p-6 border border-neutral-200 shadow-sm hover:shadow-lg transition-shadow">
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <span class="px-2 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-full">Este Mês</span>
        </div>
        <p class="text-3xl font-bold text-neutral-900 mb-1">R$ {{ number_format($faturamentoMensal ?? 0, 2, ',', '.') }}</p>
        <p class="text-sm text-neutral-600">Faturamento acumulado</p>
    </div>

    <!-- Total Agendamentos -->
    <div class="bg-white rounded-2xl p-6 border border-neutral-200 shadow-sm hover:shadow-lg transition-shadow">
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full">Todos</span>
        </div>
        <p class="text-3xl font-bold text-neutral-900 mb-1">{{ $totalAgendamentos ?? 0 }}</p>
        <p class="text-sm text-neutral-600">Total de agendamentos</p>
    </div>

    <!-- Clientes Ativos -->
    <div class="bg-white rounded-2xl p-6 border border-neutral-200 shadow-sm hover:shadow-lg transition-shadow">
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <span class="px-2 py-1 bg-purple-100 text-purple-700 text-xs font-bold rounded-full">Clientes</span>
        </div>
        <p class="text-3xl font-bold text-neutral-900 mb-1">{{ $totalClientes ?? 0 }}</p>
        <p class="text-sm text-neutral-600">Clientes cadastrados</p>
    </div>
</div>

<!-- Charts Row -->
<div class="grid lg:grid-cols-2 gap-8 mb-8">
    <!-- Recent Appointments -->
    <div class="bg-white rounded-2xl p-6 border border-neutral-200 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-neutral-900">Próximos Agendamentos</h3>
            <a href="#" class="text-sm text-primary-600 hover:text-primary-700 font-medium">Ver todos</a>
        </div>
        
        @if(isset($proximosAgendamentos) && $proximosAgendamentos->count() > 0)
            <div class="space-y-4">
                @foreach($proximosAgendamentos as $agendamento)
                <div class="flex items-center gap-4 p-4 bg-neutral-50 rounded-xl hover:bg-neutral-100 transition-colors">
                    <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-primary-500 to-primary-400 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-neutral-900 truncate">{{ $agendamento->usuario->name ?? 'Cliente' }}</p>
                        <p class="text-sm text-neutral-600">{{ $agendamento->servico->nome ?? 'Serviço' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold text-neutral-900">{{ $agendamento->data_hora_inicio->format('d/m') }}</p>
                        <p class="text-xs text-neutral-600">{{ $agendamento->data_hora_inicio->format('H:i') }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <div class="w-16 h-16 mx-auto bg-neutral-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <p class="text-sm text-neutral-600">Nenhum agendamento próximo</p>
            </div>
        @endif
    </div>

    <!-- Popular Services -->
    <div class="bg-white rounded-2xl p-6 border border-neutral-200 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-neutral-900">Serviços Populares</h3>
            <a href="#" class="text-sm text-primary-600 hover:text-primary-700 font-medium">Gerenciar</a>
        </div>
        
        @if(isset($servicosPopulares) && $servicosPopulares->count() > 0)
            <div class="space-y-4">
                @foreach($servicosPopulares as $servico)
                <div class="flex items-center justify-between p-4 bg-neutral-50 rounded-xl">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-accent-500 to-accent-400 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-neutral-900">{{ $servico->nome }}</p>
                            <p class="text-xs text-neutral-600">{{ $servico->agendamentos_count ?? 0 }} agendamentos</p>
                        </div>
                    </div>
                    <span class="text-lg font-bold text-primary-600">R$ {{ number_format($servico->preco, 0, ',', '.') }}</span>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <div class="w-16 h-16 mx-auto bg-neutral-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                    </svg>
                </div>
                <p class="text-sm text-neutral-600">Nenhum serviço cadastrado</p>
            </div>
        @endif
    </div>
</div>

<!-- Quick Actions -->
<div class="bg-gradient-to-br from-primary-50 to-accent-50 rounded-2xl p-8 border border-primary-100">
    <h3 class="text-xl font-bold text-neutral-900 mb-6">Ações Rápidas</h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="#" class="flex flex-col items-center justify-center gap-3 p-6 bg-white rounded-xl hover:shadow-lg transition-all group">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary-500 to-primary-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
            </div>
            <span class="text-sm font-semibold text-neutral-700 text-center">Novo Agendamento</span>
        </a>

        <a href="#" class="flex flex-col items-center justify-center gap-3 p-6 bg-white rounded-xl hover:shadow-lg transition-all group">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-accent-500 to-accent-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
            </div>
            <span class="text-sm font-semibold text-neutral-700 text-center">Adicionar Serviço</span>
        </a>

        <a href="#" class="flex flex-col items-center justify-center gap-3 p-6 bg-white rounded-xl hover:shadow-lg transition-all group">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
            <span class="text-sm font-semibold text-neutral-700 text-center">Ver Relatórios</span>
        </a>

        <a href="#" class="flex flex-col items-center justify-center gap-3 p-6 bg-white rounded-xl hover:shadow-lg transition-all group">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-purple-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <span class="text-sm font-semibold text-neutral-700 text-center">Configurações</span>
        </a>
    </div>
</div>
@endsection
