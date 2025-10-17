@extends('layouts.site')

@section('title', 'Promoções e Cupons | Chris Pincel Mágico')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-white via-accent-50 to-primary-50 py-12">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white border border-accent-200 shadow-sm mb-4">
                <svg class="w-4 h-4 text-accent-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                </svg>
                <span class="text-xs font-semibold text-neutral-700 uppercase tracking-wider">Economize Agora</span>
            </div>
            <h1 class="text-4xl sm:text-5xl font-bold text-neutral-900 mb-4">
                Promoções e <span class="text-brand-gradient">Cupons Exclusivos</span>
            </h1>
            <p class="text-lg text-neutral-600 max-w-2xl mx-auto">
                Aproveite nossas ofertas especiais e economize nos seus serviços favoritos. Use os cupons no momento do agendamento!
            </p>
        </div>

        @if($promocoes->count() > 0)
            <!-- Grid de Promoções -->
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                @foreach($promocoes as $promocao)
                    <div class="group bg-white rounded-3xl overflow-hidden border-2 border-primary-100 hover:border-accent-300 hover:shadow-2xl transition-all duration-300">
                        <!-- Header com Gradiente -->
                        <div class="relative bg-gradient-to-br from-accent-500 via-accent-400 to-primary-400 p-8 text-white">
                            <!-- Badge de Tipo -->
                            <div class="absolute top-4 right-4">
                                <span class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-xs font-bold uppercase tracking-wider">
                                    {{ $promocao->tipo === 'PERCENTUAL' ? 'Percentual' : 'Valor Fixo' }}
                                </span>
                            </div>

                            <!-- Valor do Desconto -->
                            <div class="mb-4">
                                <div class="text-7xl font-black mb-2 drop-shadow-lg leading-none tracking-tight">
                                    @if($promocao->tipo === 'PERCENTUAL')
                                        <span class="tabular-nums">{{ $promocao->percentual_desconto }}</span><span class="text-4xl align-top">%</span>
                                    @else
                                        <span class="text-5xl">R$</span> <span class="tabular-nums">{{ number_format($promocao->valor_desconto, 0) }}</span>
                                    @endif
                                </div>
                                <div class="text-lg font-bold text-white/95 uppercase tracking-wide">
                                    de desconto
                                </div>
                            </div>

                            <!-- Título -->
                            <h3 class="text-2xl font-bold leading-tight">
                                {{ $promocao->titulo }}
                            </h3>
                        </div>

                        <!-- Conteúdo -->
                        <div class="p-6">
                            @if($promocao->descricao)
                                <p class="text-neutral-600 mb-6 leading-relaxed">
                                    {{ $promocao->descricao }}
                                </p>
                            @endif

                            <!-- Código do Cupom -->
                            @if($promocao->codigo_cupom)
                                <div class="mb-6">
                                    <label class="block text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-2">
                                        Código do Cupom
                                    </label>
                                    <div class="flex items-center gap-2 p-4 bg-gradient-to-r from-primary-50 to-accent-50 border-2 border-dashed border-primary-300 rounded-xl">
                                        <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                        <code class="flex-1 font-mono text-lg font-bold text-primary-900 tracking-wider">
                                            {{ $promocao->codigo_cupom }}
                                        </code>
                                        <button 
                                            onclick="copiarCupom('{{ $promocao->codigo_cupom }}', this)"
                                            class="px-3 py-1 bg-primary-500 hover:bg-primary-600 text-white text-xs font-semibold rounded-lg transition-colors"
                                        >
                                            Copiar
                                        </button>
                                    </div>
                                </div>
                            @endif

                            <!-- Informações -->
                            <div class="space-y-3 mb-6">
                                <!-- Vigência -->
                                @if($promocao->inicio_vigencia || $promocao->fim_vigencia)
                                    <div class="flex items-center gap-3 text-sm text-neutral-600">
                                        <svg class="w-5 h-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span>
                                            <span class="font-medium text-neutral-900">Válido até:</span>
                                            {{ $promocao->fim_vigencia ? $promocao->fim_vigencia->format('d/m/Y') : 'Sem data limite' }}
                                        </span>
                                    </div>
                                @endif

                                <!-- Limite de Uso -->
                                @if($promocao->limite_uso)
                                    @php
                                        $usosRestantes = $promocao->limite_uso - $promocao->usos_realizados;
                                        $percentualUso = $promocao->limite_uso > 0 ? ($promocao->usos_realizados / $promocao->limite_uso) * 100 : 0;
                                    @endphp
                                    <div class="flex items-center gap-3 text-sm text-neutral-600">
                                        <svg class="w-5 h-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        <span>
                                            <span class="font-medium text-neutral-900">Disponível:</span>
                                            <span class="font-bold">{{ $usosRestantes }}</span> {{ $usosRestantes == 1 ? 'uso restante' : 'usos restantes' }}
                                        </span>
                                    </div>

                                    <!-- Barra de Progresso -->
                                    <div class="w-full bg-neutral-200 rounded-full h-2.5 overflow-hidden">
                                        <div 
                                            class="h-2.5 rounded-full transition-all duration-300 {{ $percentualUso < 50 ? 'bg-emerald-500' : ($percentualUso < 75 ? 'bg-amber-500' : 'bg-red-500') }}" 
                                            style="width: {{ min($percentualUso, 100) }}%"
                                        ></div>
                                    </div>
                                @endif
                            </div>

                            <!-- CTA -->
                            @auth
                                <a 
                                    href="{{ route('agendamentos.create') }}?cupom={{ $promocao->codigo_cupom }}" 
                                    class="block w-full px-6 py-4 bg-gradient-to-r from-primary-500 to-primary-400 hover:from-primary-600 hover:to-primary-500 text-white text-center font-bold rounded-xl shadow-lg hover:shadow-xl transition-all"
                                >
                                    Usar Agora
                                </a>
                            @else
                                <a 
                                    href="{{ route('login') }}" 
                                    class="block w-full px-6 py-4 bg-gradient-to-r from-primary-500 to-primary-400 hover:from-primary-600 hover:to-primary-500 text-white text-center font-bold rounded-xl shadow-lg hover:shadow-xl transition-all"
                                >
                                    Login para Usar
                                </a>
                            @endauth
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Paginação -->
            @if($promocoes->hasPages())
                <div class="flex justify-center">
                    {{ $promocoes->links() }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-3xl p-16 text-center border-2 border-primary-100 shadow-xl">
                <div class="w-24 h-24 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-neutral-900 mb-3">
                    Nenhuma promoção ativa no momento
                </h3>
                <p class="text-neutral-600 mb-8 max-w-md mx-auto">
                    Fique de olho! Sempre temos promoções especiais em datas comemorativas e para clientes fiéis.
                </p>
                <a 
                    href="{{ route('servicos.index') }}" 
                    class="inline-flex items-center gap-2 px-8 py-4 bg-primary-500 hover:bg-primary-600 text-white font-bold rounded-xl transition-colors shadow-lg"
                >
                    <span>Ver Serviços</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </a>
            </div>
        @endif
    </div>
</div>

<script>
function copiarCupom(codigo, botao) {
    navigator.clipboard.writeText(codigo).then(() => {
        const textoOriginal = botao.textContent;
        botao.textContent = '✓ Copiado!';
        botao.classList.add('bg-green-500');
        botao.classList.remove('bg-primary-500', 'hover:bg-primary-600');
        
        setTimeout(() => {
            botao.textContent = textoOriginal;
            botao.classList.remove('bg-green-500');
            botao.classList.add('bg-primary-500', 'hover:bg-primary-600');
        }, 2000);
    });
}
</script>
@endsection
