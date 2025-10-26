@extends('layouts.site')

@section('title', 'Minha conta | Chris Pincel Mágico')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-white via-primary-50 to-accent-50 py-12">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white border border-primary-200 shadow-sm mb-4">
                <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="text-xs font-bold text-neutral-700 uppercase tracking-wider">Área do Cliente</span>
            </div>
            <h1 class="text-4xl sm:text-5xl font-bold text-neutral-900 mb-4">
                Sua <span class="text-brand-gradient">Conta</span>
            </h1>
            <p class="text-lg text-neutral-600 max-w-3xl mx-auto">
                Controle completo dos seus agendamentos, histórico de serviços e pontos de fidelidade. Tudo em um só lugar!
            </p>
        </div>

        @if ($usuario)
        <div class="grid lg:grid-cols-[400px,1fr] gap-8">
            <!-- Sidebar - User Profile -->
            <aside class="space-y-6">
                <!-- Profile Card -->
                <div class="bg-white rounded-3xl p-8 border border-primary-200 shadow-xl sticky top-24">
                    <!-- Avatar -->
                    <div class="text-center mb-6">
                        <div class="w-24 h-24 mx-auto bg-gradient-to-br from-primary-500 to-primary-400 rounded-full flex items-center justify-center shadow-lg mb-4">
                            <span class="text-3xl font-bold text-white">
                                {{ strtoupper(substr($usuario->name, 0, 2)) }}
                            </span>
                        </div>
                        <h2 class="text-xl font-bold text-neutral-900 mb-1">{{ $usuario->name }}</h2>
                        <p class="text-sm text-neutral-600">{{ $usuario->email }}</p>
                    </div>

                    <!-- Stats -->
                    <div class="grid grid-cols-2 gap-4 mb-6 pb-6 border-b border-primary-100">
                        <div class="bg-gradient-to-br from-primary-50 to-primary-100 rounded-xl p-4 text-center">
                            <p class="text-2xl font-bold text-primary-600">{{ $agendamentos->count() }}</p>
                            <p class="text-xs text-neutral-600 font-medium">Agendamentos</p>
                        </div>
                        <div class="bg-gradient-to-br from-accent-50 to-accent-100 rounded-xl p-4 text-center">
                            <p class="text-2xl font-bold text-accent-600">{{ $agendamentosConcluidos->count() }}</p>
                            <p class="text-xs text-neutral-600 font-medium">Concluídos</p>
                        </div>
                    </div>

                    <!-- Account Info -->
                    <div class="space-y-4 mb-6">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-lg bg-primary-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-1">E-mail</p>
                                <p class="text-sm text-neutral-900 font-medium break-all">{{ $usuario->email }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-lg bg-accent-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-1">Membro desde</p>
                                <p class="text-sm text-neutral-900 font-medium">{{ $usuario->created_at?->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="space-y-3">
                        <a 
                            href="mailto:contato@chrispincelmagico.com.br?subject=Atualização de Cadastro" 
                            class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-gradient-to-r from-primary-500 to-primary-400 text-white rounded-xl font-semibold text-sm shadow-lg hover:shadow-xl hover:scale-[1.02] transition-all"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            <span>Atualizar Cadastro</span>
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button 
                                type="submit"
                                class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-white border-2 border-primary-200 text-neutral-700 rounded-xl font-semibold text-sm hover:border-primary-300 hover:shadow-lg transition-all"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span>Sair da Conta</span>
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="space-y-8">
                <!-- Appointments Section -->
                <section class="bg-white rounded-3xl p-8 sm:p-10 border border-primary-200 shadow-xl">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
                        <div>
                            <h2 class="text-2xl font-bold text-neutral-900 mb-2 flex items-center gap-2">
                                <svg class="w-6 h-6 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                Histórico de Agendamentos
                            </h2>
                            <p class="text-sm text-neutral-600">Acompanhe seus atendimentos e status</p>
                        </div>
                        <a 
                            href="{{ route('agendamentos.create') }}" 
                            class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-accent-500 to-accent-400 text-white rounded-xl font-bold text-sm shadow-lg hover:shadow-xl hover:scale-[1.02] transition-all"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            <span>Novo Agendamento</span>
                        </a>
                    </div>

                    @forelse ($agendamentos as $agendamento)
                        <article class="group border-l-4 border-primary-200 hover:border-primary-400 bg-gradient-to-r from-primary-50/50 to-transparent rounded-r-2xl p-6 mb-4 last:mb-0 transition-all hover:shadow-lg">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                <div class="flex-1">
                                    <div class="flex items-start gap-3 mb-3">
                                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary-500 to-primary-400 flex items-center justify-center flex-shrink-0 shadow-lg">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-bold text-neutral-900 mb-1">
                                                {{ $agendamento->servico->nome ?? 'Serviço removido' }}
                                            </h3>
                                            <div class="flex flex-wrap items-center gap-3 text-sm text-neutral-600">
                                                <span class="flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    {{ $agendamento->data_hora_inicio->format('d/m/Y') }}
                                                </span>
                                                <span class="flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    {{ $agendamento->data_hora_inicio->format('H:i') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex flex-col sm:items-end gap-3">
                                    @php
                                        $statusConfig = [
                                            'pendente' => ['color' => 'amber', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                                            'confirmado' => ['color' => 'blue', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                            'concluido' => ['color' => 'emerald', 'icon' => 'M5 13l4 4L19 7'],
                                            'cancelado' => ['color' => 'red', 'icon' => 'M6 18L18 6M6 6l12 12'],
                                        ];
                                        $statusChave = strtolower($agendamento->status);
                                        $config = $statusConfig[$statusChave] ?? $statusConfig['pendente'];
                                    @endphp

                                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-{{ $config['color'] }}-100 border border-{{ $config['color'] }}-200 text-{{ $config['color'] }}-700 text-xs font-bold uppercase tracking-wider">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $config['icon'] }}" />
                                        </svg>
                                        {{ ucfirst(strtolower($agendamento->status)) }}
                                    </span>

                                    <div class="flex items-center gap-2">
                                        <span class="text-xs text-neutral-500">Valor total:</span>
                                        <span class="text-lg font-bold text-brand-gradient">
                                            R$ {{ number_format($agendamento->valor_total, 2, ',', '.') }}
                                        </span>
                                    </div>

                                    @if(strcasecmp($agendamento->status, 'concluido') === 0)
                                        @if($agendamento->avaliacao)
                                            <!-- Já avaliado -->
                                            <div class="flex items-center gap-2 px-4 py-2 bg-emerald-50 border border-emerald-200 rounded-lg">
                                                <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                                <div class="text-left">
                                                    <p class="text-xs font-bold text-emerald-700">{{ $agendamento->avaliacao->nota }} estrelas</p>
                                                    <p class="text-xs text-emerald-600">
                                                        {{ $agendamento->avaliacao->publicado ? 'Publicada' : 'Em moderação' }}
                                                    </p>
                                                </div>
                                            </div>
                                        @else
                                            <!-- Botão para avaliar -->
                                            <button 
                                                onclick="openModal('avaliar-{{ $agendamento->id }}')"
                                                class="flex items-center gap-2 px-5 py-2.5 rounded-lg shadow-md hover:shadow-xl hover:scale-105 transition-all duration-200 font-bold text-sm"
                                                style="background: linear-gradient(135deg, #B57B7B 0%, #E0A3A3 100%); color: white !important;"
                                                onmouseover="this.style.background='linear-gradient(135deg, #9d6868 0%, #ce8f8f 100%)'"
                                                onmouseout="this.style.background='linear-gradient(135deg, #B57B7B 0%, #E0A3A3 100%)'"
                                            >
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" style="color: white;">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                                <span style="color: white;">Avaliar Serviço</span>
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="text-center py-16">
                            <div class="w-20 h-20 mx-auto bg-primary-100 rounded-full flex items-center justify-center mb-6">
                                <svg class="w-10 h-10 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-neutral-900 mb-2">Nenhum Agendamento Ainda</h3>
                            <p class="text-neutral-600 mb-6">Faça seu primeiro agendamento e comece a aproveitar nossos serviços!</p>
                            <a 
                                href="{{ route('agendamentos.create') }}" 
                                class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-primary-500 to-primary-400 text-white rounded-xl font-bold shadow-lg hover:shadow-xl hover:scale-[1.02] transition-all"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                <span>Agendar Agora</span>
                            </a>
                        </div>
                    @endforelse
                </section>

                <!-- Loyalty Program -->
                <section class="bg-white rounded-3xl p-8 border border-accent-200 shadow-xl">
                    @php
                        $fidelidade = $resumoFidelidade ?? [];
                        $saldoPontos = $fidelidade['pontos_atuais'] ?? 0;
                        $saldoEmReais = $fidelidade['valor_resgate_disponivel'] ?? 0;
                        $acumulado = $fidelidade['pontos_acumulados'] ?? 0;
                        $nivelAtual = $fidelidade['nivel_atual']['nome'] ?? 'Essencial';
                        $descricaoNivel = $fidelidade['nivel_atual']['descricao'] ?? 'Continue acumulando experiências para liberar benefícios especiais.';
                        $proximoNivel = $fidelidade['proximo_nivel']['nome'] ?? null;
                        $progressoNivel = $fidelidade['progresso_percentual'] ?? 0;
                        $faltamProximo = $fidelidade['faltam_para_proximo'] ?? 0;
                        $podeResgatar = $fidelidade['pode_resgatar'] ?? false;
                        $resgateMinimo = $fidelidade['resgate_minimo'] ?? 0;
                        $faltamResgate = $fidelidade['faltam_para_resgate'] ?? 0;
                        $valorPorPonto = $fidelidade['valor_por_ponto'] ?? 0;
                        $pontosPorReal = $fidelidade['pontos_por_real'] ?? 0;
                        $percentualMaximo = $fidelidade['maximo_percentual_resgate'] ?? 0;
                    @endphp

                    <div class="flex flex-col xl:flex-row gap-8">
                        <div class="xl:w-1/2 space-y-6">
                            <div>
                                <div class="flex flex-wrap items-center justify-between gap-3">
                                    <h3 class="text-2xl font-bold text-neutral-900 flex items-center gap-2">
                                        <svg class="w-6 h-6 text-accent-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a3 3 0 11-6 0 3 3 0 016 0zM5 21a7 7 0 0114 0H5z" />
                                        </svg>
                                        Programa de Fidelidade
                                    </h3>
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-accent-100 text-accent-700 text-xs font-bold uppercase tracking-wider">
                                        {{ $nivelAtual }}
                                    </span>
                                </div>
                                <p class="text-sm text-neutral-600 mt-2">{{ $descricaoNivel }}</p>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="bg-gradient-to-br from-accent-50 to-white border border-accent-200 rounded-2xl p-5 shadow-sm">
                                    <p class="text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-1">Pontos disponíveis</p>
                                    <p class="text-3xl font-bold text-accent-600">{{ $saldoPontos }}</p>
                                    <p class="text-sm text-neutral-500">≈ R$ {{ number_format($saldoEmReais, 2, ',', '.') }}</p>
                                </div>
                                <div class="bg-gradient-to-br from-primary-50 to-white border border-primary-200 rounded-2xl p-5 shadow-sm">
                                    <p class="text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-1">Pontos acumulados</p>
                                    <p class="text-3xl font-bold text-primary-600">{{ $acumulado }}</p>
                                    <p class="text-sm text-neutral-500">Ganhe {{ $pontosPorReal }} ponto(s) a cada R$ 1,00 investido</p>
                                </div>
                            </div>

                            <div>
                                <div class="flex justify-between items-center text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-2">
                                    <span>{{ $nivelAtual }}</span>
                                    <span>{{ $proximoNivel ?? 'Nível Máximo' }}</span>
                                </div>
                                <div class="h-3 bg-accent-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-accent-500 to-primary-500" style="width: {{ min(100, max(0, $proximoNivel ? $progressoNivel : 100)) }}%;"></div>
                                </div>
                                <p class="text-xs text-neutral-500 mt-2">
                                    @if($proximoNivel)
                                        Faltam <span class="font-semibold text-neutral-700">{{ $faltamProximo }}</span> pontos para alcançar o nível {{ $proximoNivel }}.
                                    @else
                                        Você atingiu o nível máximo. Obrigado por ser parte da nossa história! ✨
                                    @endif
                                </p>
                            </div>

                            <div class="space-y-3">
                                <div class="bg-accent-50 border border-accent-200 rounded-xl p-4 flex items-start gap-3 text-sm text-neutral-600">
                                    <svg class="w-5 h-5 text-accent-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 11-2 2h2a2 2 0 012-2zm0 0v2m0 8v2m0-2a2 2 0 11-2-2h2a2 2 0 012 2zm0 0v2m8-10h-2m2 0a2 2 0 11-2 2V8a2 2 0 012-2zm0 0h-2m-8 0H6m2 0a2 2 0 11-2 2V8a2 2 0 012-2zm0 0H6m0 8H4m2 0a2 2 0 11-2 2v-2a2 2 0 012-2zm0 0H4m16 0h-2m2 0a2 2 0 11-2 2v-2a2 2 0 012-2zm0 0h-2" />
                                    </svg>
                                    <div>
                                        @if($podeResgatar)
                                            <p>Você já pode usar seus pontos no próximo agendamento.</p>
                                        @else
                                            <p>Faltam <span class="font-semibold text-neutral-700">{{ $faltamResgate }}</span> pontos para liberar o resgate mínimo de {{ $resgateMinimo }} pontos.</p>
                                        @endif
                                        <p class="text-xs text-neutral-500 mt-1">Cada ponto vale R$ {{ number_format($valorPorPonto, 2, ',', '.') }} em desconto.</p>
                                    </div>
                                </div>
                                <div class="bg-white border border-dashed border-neutral-200 rounded-xl p-4 text-xs text-neutral-500">
                                    Combine cupons e pontos para potencializar seus benefícios. O desconto com pontos cobre até {{ (int) $percentualMaximo }}% do valor do serviço.
                                </div>
                            </div>
                        </div>

                        <div class="xl:w-1/2 space-y-4">
                            <div class="flex items-center justify-between">
                                <h4 class="text-lg font-bold text-neutral-900 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M5 6h14M7 18h10" />
                                    </svg>
                                    Histórico de Pontos
                                </h4>
                                <span class="text-xs text-neutral-500 uppercase tracking-wider">Últimas movimentações</span>
                            </div>

                            <div class="space-y-3">
                                @forelse($transacoesPontos as $transacao)
                                    <div class="flex items-center justify-between gap-4 p-4 rounded-2xl border border-neutral-200 bg-neutral-50/60 shadow-sm">
                                        <div class="flex-1">
                                            <p class="text-sm font-semibold text-neutral-900">{{ $transacao->descricao ?? ($transacao->tipo === 'CREDITO' ? 'Pontos acumulados' : 'Resgate de pontos') }}</p>
                                            <p class="text-xs text-neutral-500 mt-1">
                                                {{ optional($transacao->registrado_em ?? $transacao->created_at)->format('d/m/Y H:i') }}
                                                @if($transacao->agendamento_id)
                                                    • Agendamento #{{ $transacao->agendamento_id }}
                                                @endif
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm font-bold {{ $transacao->tipo === 'CREDITO' ? 'text-emerald-600' : 'text-red-500' }}">
                                                {{ $transacao->tipo === 'CREDITO' ? '+' : '-' }}{{ $transacao->pontos }} pts
                                            </p>
                                            @if($transacao->valor_referencia)
                                                <p class="text-xs text-neutral-500">Ref.: R$ {{ number_format($transacao->valor_referencia, 2, ',', '.') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="bg-white border border-dashed border-accent-200 rounded-2xl p-6 text-center text-sm text-neutral-500">
                                        Nenhuma movimentação ainda. Conclua um agendamento para começar a acumular pontos. ✨
                                    </div>
                                @endforelse
                            </div>

                            <p class="text-xs text-neutral-500">Precisa do extrato completo? Fale com a gente pelo WhatsApp que enviamos rapidinho.</p>
                        </div>
                    </div>
                </section>
            </main>
        </div>

        @else
        <!-- Not Logged In -->
        <div class="bg-white rounded-3xl p-12 border border-primary-200 shadow-2xl text-center max-w-2xl mx-auto">
            <div class="w-20 h-20 mx-auto bg-primary-100 rounded-full flex items-center justify-center mb-6">
                <svg class="w-10 h-10 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-neutral-900 mb-3">Faça Login para Continuar</h2>
            <p class="text-neutral-600 mb-8">Acesse sua conta para visualizar seus agendamentos, histórico e pontos de fidelidade.</p>
            <a 
                href="{{ route('login') }}" 
                class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-primary-500 to-primary-400 text-white rounded-xl font-bold shadow-lg hover:shadow-xl hover:scale-[1.02] transition-all"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                </svg>
                <span>Entrar na Minha Conta</span>
            </a>
        </div>
        @endif
    </div>

    <!-- Modais de Avaliação -->
    @if($usuario && $agendamentos)
        @foreach($agendamentos as $agendamento)
            @if(strcasecmp($agendamento->status, 'concluido') === 0 && !$agendamento->avaliacao)
                <div id="avaliar-{{ $agendamento->id }}" class="hidden fixed inset-0 bg-black/70 backdrop-blur-sm z-50 overflow-y-auto" onclick="if(event.target === this) closeModal('avaliar-{{ $agendamento->id }}')">
                    <div class="min-h-screen px-4 py-8 flex items-center justify-center">
                        <div class="bg-white rounded-3xl w-full max-w-2xl shadow-2xl relative my-8 animate-fadeIn" onclick="event.stopPropagation()" style="animation: modalSlideIn 0.3s ease-out;">
                        <!-- Header com Gradiente -->
                        <div class="p-6 sm:p-8 text-center relative" style="background: linear-gradient(135deg, #B57B7B 0%, #E0A3A3 50%, #C0E0E0 100%);">
                            <button 
                                onclick="closeModal('avaliar-{{ $agendamento->id }}')"
                                class="absolute top-3 right-3 sm:top-4 sm:right-4 transition-colors bg-black/10 hover:bg-black/20 rounded-full p-2"
                                type="button"
                                style="color: white;"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>

                            <div class="w-16 h-16 sm:w-20 sm:h-20 mx-auto bg-white rounded-full flex items-center justify-center mb-3 sm:mb-4 shadow-lg">
                                <svg class="w-8 h-8 sm:w-10 sm:h-10" fill="currentColor" viewBox="0 0 20 20" style="color: #B57B7B;">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            </div>
                            <h3 class="text-2xl sm:text-3xl font-bold mb-2 drop-shadow-lg px-4" style="color: white;">Como foi sua experiência?</h3>
                            <p class="font-medium text-sm sm:text-base px-4" style="color: white;">{{ $agendamento->servico->nome }}</p>
                            <p class="text-xs sm:text-sm mt-1" style="color: rgba(255, 255, 255, 0.9);">{{ $agendamento->data_hora_inicio->format('d/m/Y \à\s H:i') }}</p>
                        </div>                            <!-- Body -->
                            <div class="p-6 sm:p-8">
                            <form method="POST" action="{{ route('avaliacoes.store') }}" class="space-y-6">
                                @csrf
                                <input type="hidden" name="agendamento_id" value="{{ $agendamento->id }}">

                                <!-- Seletor de Estrelas -->
                                <div class="text-center rounded-2xl p-4 sm:p-6 border-2" style="background: linear-gradient(to bottom right, #F8E8E8, #F3D0D0); border-color: #E0A3A3;">
                                    <label class="block text-base sm:text-lg font-bold mb-3 sm:mb-4" style="color: #1f2937;">Sua avaliação</label>
                                    <div class="flex justify-center gap-1 sm:gap-2 mb-3">
                                        @for($i = 1; $i <= 5; $i++)
                                            <button 
                                                type="button"
                                                onclick="selectStar({{ $agendamento->id }}, {{ $i }})"
                                                class="star-btn-{{ $agendamento->id }} text-4xl sm:text-5xl md:text-6xl transition-all hover:scale-110 sm:hover:scale-125 focus:outline-none"
                                                data-rating="{{ $i }}"
                                                style="color: {{ $i <= 5 ? '#fbbf24' : '#d1d5db' }}; background: none; border: none; padding: 0; cursor: pointer;"
                                            >
                                                ★
                                            </button>
                                        @endfor
                                    </div>
                                    <input type="hidden" name="nota" id="nota-{{ $agendamento->id }}" value="5" required>
                                    <div class="flex items-center justify-center">
                                        <div class="px-3 sm:px-4 py-1.5 sm:py-2 bg-white rounded-full border-2" style="border-color: #B57B7B;">
                                            <p class="text-sm sm:text-base font-bold" id="rating-text-{{ $agendamento->id }}" style="color: #B57B7B;">⭐ Excelente</p>
                                        </div>
                                    </div>
                                </div>
                                <!-- Comentário -->
                                <div>
                                    <label class="block text-sm font-bold mb-2 sm:mb-3 flex items-center gap-2" style="color: #1f2937;">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #B57B7B;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                        </svg>
                                        <span class="text-xs sm:text-sm" style="color: #1f2937;">Conte sua experiência (opcional)</span>
                                    </label>
                                    <textarea 
                                        name="comentario" 
                                        rows="4" 
                                        maxlength="1000"
                                        class="w-full px-3 sm:px-4 py-2 sm:py-3 border-2 rounded-xl resize-none text-xs sm:text-sm bg-white transition-all"
                                        placeholder="Compartilhe detalhes sobre o atendimento, qualidade do serviço, ambiente... Seu feedback é muito importante! ✨"
                                        style="color: #1f2937; border-color: #d1d5db;"
                                        onfocus="this.style.borderColor='#B57B7B'; this.style.outline='2px solid rgba(181, 123, 123, 0.2)'"
                                        onblur="this.style.borderColor='#d1d5db'; this.style.outline='none'"
                                    ></textarea>
                                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-1 sm:gap-0 mt-2">
                                        <p class="text-xs" style="color: #6b7280;">💡 Quanto mais detalhes, melhor!</p>
                                        <p class="text-xs" style="color: #6b7280;">Máximo: 1000 caracteres</p>
                                    </div>
                                </div>

                                <!-- Botões -->
                                <div class="flex flex-col sm:flex-row gap-3 pt-2">
                                    <button 
                                        type="button"
                                        onclick="closeModal('avaliar-{{ $agendamento->id }}')"
                                        class="w-full sm:flex-1 px-4 sm:px-6 py-3 sm:py-3.5 border-2 border-neutral-300 font-bold rounded-xl hover:bg-neutral-100 hover:border-neutral-400 transition-all text-sm sm:text-base order-2 sm:order-1"
                                        style="color: #374151;"
                                    >
                                        Cancelar
                                    </button>
                                    <button 
                                        type="submit"
                                        class="w-full sm:flex-1 px-4 sm:px-6 py-3 sm:py-3.5 font-bold rounded-xl shadow-lg hover:shadow-xl hover:scale-[1.02] transition-all duration-200 flex items-center justify-center gap-2 text-sm sm:text-base order-1 sm:order-2"
                                        style="background: linear-gradient(135deg, #B57B7B 0%, #E0A3A3 100%); color: white !important;"
                                        onmouseover="this.style.background='linear-gradient(135deg, #9d6868 0%, #ce8f8f 100%)'"
                                        onmouseout="this.style.background='linear-gradient(135deg, #B57B7B 0%, #E0A3A3 100%)'"
                                    >
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: white;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span style="color: white;">Enviar Avaliação</span>
                                    </button>
                                </div>

                                <!-- Aviso -->
                                <div class="flex items-start gap-2 sm:gap-3 p-3 sm:p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20" style="color: #3b82f6;">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                    </svg>
                                    <div>
                                        <p class="text-xs sm:text-sm font-semibold" style="color: #1e3a8a;">Moderação de Avaliações</p>
                                        <p class="text-xs mt-1" style="color: #1e40af;">Sua avaliação será analisada pela nossa equipe antes de ser publicada no site. Obrigado por contribuir!</p>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    @endif
</div>

<style>
@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: scale(0.95) translateY(-20px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

/* Scroll suave para o modal */
.overflow-y-auto {
    -webkit-overflow-scrolling: touch;
}

/* Ocultar scrollbar mas manter funcionalidade */
.overflow-y-auto::-webkit-scrollbar {
    width: 8px;
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: rgba(0, 0, 0, 0.1);
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background: rgba(0, 0, 0, 0.3);
    border-radius: 4px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: rgba(0, 0, 0, 0.5);
}
</style>

<script>
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    // Animação suave
    setTimeout(() => {
        modal.querySelector('.bg-white').style.transform = 'scale(1)';
        modal.querySelector('.bg-white').style.opacity = '1';
    }, 10);
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function selectStar(agendamentoId, rating) {
    // Atualiza o valor hidden
    document.getElementById('nota-' + agendamentoId).value = rating;
    
    // Atualiza as estrelas visualmente usando a classe correta
    const stars = document.querySelectorAll('.star-btn-' + agendamentoId);
    stars.forEach((star, index) => {
        if (index < rating) {
            star.style.color = '#fbbf24'; // Amarelo para estrelas selecionadas
        } else {
            star.style.color = '#d1d5db'; // Cinza para estrelas não selecionadas
        }
    });
    
    // Atualiza o texto descritivo com emojis
    const descriptions = [
        '😞 Péssimo',
        '😕 Ruim', 
        '😐 Regular',
        '😊 Bom',
        '⭐ Excelente'
    ];
    const colors = [
        '#dc2626', // red-600 - Péssimo
        '#ea580c', // orange-600 - Ruim
        '#ca8a04', // yellow-600 - Regular
        '#C0E0E0', // mint-accent - Bom
        '#B57B7B'  // rose-metallic - Excelente
    ];
    
    const textElement = document.getElementById('rating-text-' + agendamentoId);
    textElement.textContent = descriptions[rating - 1];
    textElement.style.color = colors[rating - 1];
}

// Fechar modal ao clicar fora (já tratado no onclick do backdrop)
// Fechar modal com tecla ESC
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const modals = document.querySelectorAll('[id^="avaliar-"]');
        modals.forEach(modal => {
            if (!modal.classList.contains('hidden')) {
                closeModal(modal.id);
            }
        });
    }
});
</script>

@endsection
