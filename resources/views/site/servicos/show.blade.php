@extends('layouts.site')

@section('title', $servico->nome . ' | Chris Pincel Mágico')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-white to-primary-50 py-8">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-sm text-neutral-500 mb-8">
            <a href="{{ route('home') }}" class="hover:text-primary-600 transition-colors">Início</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <a href="{{ route('servicos.index') }}" class="hover:text-primary-600 transition-colors">Serviços</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-neutral-900 font-medium">{{ $servico->nome }}</span>
        </nav>

        <div class="grid lg:grid-cols-[1fr,400px] gap-12">
            <!-- Main Content -->
            <div class="space-y-8">
                <!-- Hero Section -->
                <div class="bg-white rounded-3xl overflow-hidden border border-primary-100 shadow-lg">
                    @if($servico->imagem_capa)
                        <div class="w-full h-48 sm:h-56 md:h-64 lg:h-72 overflow-hidden">
                            <img src="{{ asset('storage/' . $servico->imagem_capa) }}" alt="{{ $servico->nome }}" class="w-full h-full object-cover">
                        </div>
                    @endif
                    <div class="p-8">
                        <h1 class="text-4xl font-bold text-neutral-900 mb-4">
                            {{ $servico->nome }}
                        </h1>
                        <p class="text-lg text-neutral-600 leading-relaxed">
                            {{ $servico->descricao ?? 'Transforme seu visual com técnicas profissionais e produtos premium. Entre em contato para mais detalhes sobre este serviço.' }}
                        </p>
                    </div>
                </div>

                <!-- Pricing Info -->
                <div class="grid sm:grid-cols-2 gap-6">
                    <div class="bg-gradient-to-br from-primary-50 to-primary-100 rounded-2xl p-6 border border-primary-200">
                        <div class="flex items-start gap-3">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary-500 to-primary-400 flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-wider text-neutral-600 font-semibold mb-1">Duração</p>
                                <p class="text-3xl font-bold text-neutral-900">{{ $servico->duracao_minutos }}<span class="text-lg text-neutral-600"> min</span></p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-accent-50 to-accent-100 rounded-2xl p-6 border border-accent-200">
                        <div class="flex items-start gap-3">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-accent-500 to-accent-400 flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-wider text-neutral-600 font-semibold mb-1">Investimento</p>
                                <p class="text-3xl font-bold text-brand-gradient">R$ {{ number_format($servico->preco, 2, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                    @if($servico->preco_retoque)
                    <div class="sm:col-span-2 bg-white rounded-2xl p-6 border border-primary-200">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-neutral-900 mb-1">Retoque com Desconto</p>
                                <p class="text-sm text-neutral-600 mb-2">
                                    Apenas <span class="font-bold text-primary-600">R$ {{ number_format($servico->preco_retoque, 2, ',', '.') }}</span> 
                                    se agendado em até <span class="font-semibold">{{ $servico->dias_para_retoque }} dias</span> após o atendimento original
                                </p>
                                <p class="text-xs text-neutral-500">Economize {{ number_format((1 - $servico->preco_retoque / $servico->preco) * 100, 0) }}% no retoque!</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- How It Works -->
                <div class="bg-white rounded-3xl p-8 border border-primary-100">
                    <h2 class="text-2xl font-bold text-neutral-900 mb-6">Como Funciona</h2>
                    <div class="space-y-4">
                        <div class="flex gap-4">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-primary-500 to-primary-400 flex items-center justify-center flex-shrink-0 text-white font-bold text-sm">1</div>
                            <div>
                                <p class="font-semibold text-neutral-900 mb-1">Agendamento Online</p>
                                <p class="text-sm text-neutral-600">Escolha data e horário disponíveis. Confirmação imediata com pagamento do sinal via PIX.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-primary-500 to-primary-400 flex items-center justify-center flex-shrink-0 text-white font-bold text-sm">2</div>
                            <div>
                                <p class="font-semibold text-neutral-900 mb-1">Lembretes Automáticos</p>
                                <p class="text-sm text-neutral-600">Receba notificação 24h antes por WhatsApp com link para confirmar sua presença.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-primary-500 to-primary-400 flex items-center justify-center flex-shrink-0 text-white font-bold text-sm">3</div>
                            <div>
                                <p class="font-semibold text-neutral-900 mb-1">Programa de Fidelidade</p>
                                <p class="text-sm text-neutral-600">Ganhe 1 ponto para cada R$ 1,00 gasto. Troque por descontos em serviços futuros!</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar / CTA -->
            <aside class="lg:space-y-6">
                <!-- Sticky CTA Card -->
                <div class="lg:sticky lg:top-24 bg-gradient-to-br from-primary-500 via-primary-400 to-accent-400 rounded-3xl p-8 text-white shadow-2xl mb-6">
                    <h3 class="text-2xl font-bold mb-3">Garanta seu Horário</h3>
                    <p class="text-primary-50 mb-6 text-sm leading-relaxed">
                        Selecione data e horário disponíveis. O valor do sinal será calculado automaticamente na confirmação.
                    </p>
                    
                    @auth
                        <a 
                            href="{{ route('agendamentos.create', ['servico' => $servico->id]) }}" 
                            class="block w-full text-center px-6 py-4 bg-white hover:bg-primary-50 rounded-xl font-bold shadow-lg hover:shadow-xl transition-all mb-4"
                            style="color: #e0a3a3 !important;"
                        >
                            Agendar Agora
                        </a>
                    @else
                        <a 
                            href="{{ route('login') }}" 
                            class="block w-full text-center px-6 py-4 bg-white hover:bg-primary-50 rounded-xl font-bold shadow-lg hover:shadow-xl transition-all mb-4"
                            style="color: #dc2626 !important;"
                        >
                            Fazer Login para Agendar
                        </a>
                    @endauth

                    <div class="pt-6 border-t border-white/20">
                        <p class="text-xs text-primary-100 mb-3 font-medium">Alguma dúvida?</p>
                        <a 
                            href="{{ whatsapp_link('Olá! Gostaria de informações sobre o serviço: ' . $servico->nome) }}" 
                            target="_blank"
                            class="flex items-center gap-3 text-sm text-white hover:text-primary-100 transition-colors"
                        >
                            <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold">Fale pelo WhatsApp</p>
                                <p class="text-xs text-primary-100">Tire suas dúvidas agora</p>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Back Link -->
                <a 
                    href="{{ route('servicos.index') }}" 
                    class="flex items-center justify-center gap-2 w-full px-6 py-3 bg-white border-2 border-primary-200 text-primary-700 rounded-xl font-medium hover:bg-primary-50 transition-colors"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Voltar ao Catálogo</span>
                </a>
            </aside>
        </div>
    </div>
</div>
@endsection
