@extends('layouts.admin')

@section('page-title', 'Configurações')
@section('page-description', 'Gerencie as configurações do sistema')

@section('content')
<div class="space-y-6">
    
    @if(session('status'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.configuracoes.update') }}" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Informações do Site -->
        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 overflow-hidden">
            <div class="p-6 border-b border-neutral-200 bg-gradient-to-br from-primary-50 to-white">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-primary-500 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-neutral-900">Informações do Site</h3>
                        <p class="text-sm text-neutral-600">Dados básicos exibidos no site público</p>
                    </div>
                </div>
            </div>
            
            <div class="p-6 grid md:grid-cols-2 gap-6">
                <!-- Nome do Site -->
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 mb-2">
                        Nome do Site
                    </label>
                    <input 
                        type="text" 
                        name="configuracoes[site_nome][valor]" 
                        value="{{ config_site('site_nome', 'Chris Pincel Mágico') }}"
                        placeholder="Chris Pincel Mágico"
                        class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                    >
                    <p class="text-xs text-neutral-500 mt-1">Nome exibido no topo do site e títulos</p>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 mb-2">
                        E-mail de Contato
                    </label>
                    <input 
                        type="email" 
                        name="configuracoes[site_email][valor]" 
                        value="{{ config_site('site_email', 'contato@chrispincelmagico.com') }}"
                        placeholder="contato@chrispincelmagico.com"
                        class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                    >
                    <p class="text-xs text-neutral-500 mt-1">E-mail principal para contatos</p>
                </div>

                <!-- Telefone -->
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 mb-2">
                        Telefone
                    </label>
                    <input 
                        type="tel" 
                        name="configuracoes[site_telefone][valor]" 
                        value="{{ config_site('site_telefone', '(85) 98765-4321') }}"
                        placeholder="(85) 98765-4321"
                        class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                    >
                    <p class="text-xs text-neutral-500 mt-1">Telefone fixo ou celular principal</p>
                </div>

                <!-- WhatsApp (DESTAQUE) -->
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 mb-2 flex items-center gap-2">
                        Número do WhatsApp
                        <span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs font-bold rounded uppercase">Importante</span>
                    </label>
                    <input 
                        type="tel" 
                        name="configuracoes[whatsapp_numero][valor]" 
                        value="{{ config_site('whatsapp_numero', '+5585987654321') }}"
                        placeholder="+5585987654321"
                        class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                    >
                    <p class="text-xs text-neutral-500 mt-1">Formato internacional: +55 DDD NÚMERO</p>
                </div>

                <!-- Endereço Studio 1 - Floriano, PI -->
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 mb-2 flex items-center gap-2">
                        📍 Estúdio Floriano - PI
                    </label>
                    <input 
                        type="text" 
                        name="configuracoes[endereco_floriano][valor]" 
                        value="{{ config_site('endereco_floriano', 'Floriano, Piauí') }}"
                        placeholder="Rua, número - Bairro, Floriano - PI"
                        class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                    >
                    <p class="text-xs text-neutral-500 mt-1">Endereço completo do estúdio em Floriano</p>
                </div>

                <!-- Endereço Studio 2 - Barão de Grajaú, MA -->
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 mb-2 flex items-center gap-2">
                        📍 Estúdio Barão de Grajaú - MA
                    </label>
                    <input 
                        type="text" 
                        name="configuracoes[endereco_barao][valor]" 
                        value="{{ config_site('endereco_barao', 'Barão de Grajaú, Maranhão') }}"
                        placeholder="Rua, número - Bairro, Barão de Grajaú - MA"
                        class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                    >
                    <p class="text-xs text-neutral-500 mt-1">Endereço completo do estúdio em Barão de Grajaú</p>
                </div>

                <!-- Instagram -->
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 mb-2">
                        Instagram
                    </label>
                    <input 
                        type="text" 
                        name="configuracoes[site_instagram][valor]" 
                        value="{{ config_site('site_instagram', '@chrispincelmagico') }}"
                        placeholder="@chrispincelmagico"
                        class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                    >
                    <p class="text-xs text-neutral-500 mt-1">Usuário do Instagram (com @)</p>
                </div>

                <!-- Facebook -->
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 mb-2">
                        Facebook
                    </label>
                    <input 
                        type="text" 
                        name="configuracoes[site_facebook][valor]" 
                        value="{{ config_site('site_facebook', '') }}"
                        placeholder="Nome de usuário ou URL"
                        class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                    >
                    <p class="text-xs text-neutral-500 mt-1">Perfil do Facebook (opcional)</p>
                </div>
            </div>
        </div>

        <!-- Sistema de Pontos e Agendamentos -->
        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 overflow-hidden">
            <div class="p-6 border-b border-neutral-200 bg-gradient-to-br from-accent-50 to-white">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-accent-500 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-neutral-900">Sistema de Pontos e Agendamentos</h3>
                        <p class="text-sm text-neutral-600">Configurações de regras de negócio</p>
                    </div>
                </div>
            </div>

            <div class="p-6 grid md:grid-cols-3 gap-6">
                @php
                $configsNumericas = [
                    'dias_expiracao_pontos' => ['label' => 'Dias para Expiração de Pontos', 'default' => 365, 'desc' => 'Quantos dias até os pontos expirarem'],
                    'horario_lembrete_horas' => ['label' => 'Horas Antes do Lembrete', 'default' => 24, 'desc' => 'Quantas horas antes enviar lembrete'],
                    'pontos_por_real' => ['label' => 'Pontos por R$ 1,00', 'default' => 1, 'desc' => 'Quantidade de pontos ganhos por real gasto'],
                    'desconto_pontos_percentual' => ['label' => 'Desconto Pontos (%)', 'default' => 30, 'desc' => 'Percentual máximo de desconto com pontos'],
                    'taxa_cancelamento_percentual' => ['label' => 'Taxa de Cancelamento (%)', 'default' => 50, 'desc' => 'Taxa cobrada em cancelamentos tardios'],
                    'minutos_antecedencia_cancelamento' => ['label' => 'Antecedência p/ Cancelamento', 'default' => 720, 'desc' => 'Minutos mínimos para cancelar sem taxa'],
                    'limite_agendamentos_simultaneos' => ['label' => 'Agendamentos Simultâneos', 'default' => 3, 'desc' => 'Máximo de agendamentos por cliente'],
                    'dias_antecedencia_agendamento' => ['label' => 'Dias de Antecedência', 'default' => 30, 'desc' => 'Dias máximos de antecedência para agendar'],
                    'tempo_medio_atendimento' => ['label' => 'Tempo Médio (min)', 'default' => 60, 'desc' => 'Duração padrão dos atendimentos'],
                ];
                @endphp

                @foreach($configsNumericas as $chave => $config)
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 mb-2">
                        {{ $config['label'] }}
                    </label>
                    <input 
                        type="number" 
                        name="configuracoes[{{ $chave }}][valor]" 
                        value="{{ config_site($chave, $config['default']) }}"
                        placeholder="{{ $config['default'] }}"
                        min="0"
                        step="1"
                        class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-accent-500 focus:border-transparent"
                    >
                    <p class="text-xs text-neutral-500 mt-1">{{ $config['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Botão Salvar -->
        <div class="flex items-center justify-between bg-white rounded-xl shadow-sm border border-neutral-200 p-6">
            <div>
                <p class="text-sm text-neutral-600">
                    <span class="inline-flex items-center gap-1">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        As alterações serão aplicadas imediatamente em todo o site
                    </span>
                </p>
            </div>
            <button 
                type="submit"
                class="px-6 py-3 bg-gradient-to-r from-primary-500 to-accent-500 text-white font-bold rounded-lg hover:from-primary-600 hover:to-accent-600 transition-all duration-200 shadow-lg hover:shadow-xl flex items-center gap-2"
            >
                💾 Salvar Configurações
            </button>
        </div>
    </form>

    <!-- Informações do Sistema -->
    <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-6">
        <h3 class="text-lg font-bold text-neutral-900 mb-6 flex items-center gap-2">
            <svg class="w-5 h-5 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Informações do Sistema
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="p-4 bg-neutral-50 rounded-lg border border-neutral-200">
                <div class="text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-1">Versão do Laravel</div>
                <div class="text-2xl font-bold text-neutral-900">{{ app()->version() }}</div>
            </div>
            <div class="p-4 bg-neutral-50 rounded-lg border border-neutral-200">
                <div class="text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-1">Versão do PHP</div>
                <div class="text-2xl font-bold text-neutral-900">{{ PHP_VERSION }}</div>
            </div>
            <div class="p-4 bg-neutral-50 rounded-lg border border-neutral-200">
                <div class="text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-1">Ambiente</div>
                <div class="text-2xl font-bold text-neutral-900">
                    <span class="px-3 py-1 rounded-full text-sm {{ app()->environment('production') ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                        {{ app()->environment() }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
