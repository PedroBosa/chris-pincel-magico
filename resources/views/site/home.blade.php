@extends('layouts.site')

@section('title', 'Beleza autoral com tecnologia | Chris Pincel Mágico')

@section('content')
<!-- Hero Section -->
<section class="relative overflow-hidden bg-gradient-to-br from-white via-primary-50 to-accent-100 py-20 sm:py-28">
    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiNCNTdCN0IiIGZpbGwtb3BhY2l0eT0iMC4wNSI+PHBhdGggZD0iTTM2IDE0YzMuMzEgMCA2LTIuNjkgNi02cy0yLjY5LTYtNi02LTYgMi42OS02IDYgMi42OSA2IDYgNnptLTEyIDBjMy4zMSAwIDYtMi42OSA2LTZzLTIuNjktNi02LTYtNiAyLjY5LTYgNiAyLjY5IDYgNiA2eiIvPjwvZz48L2c+PC9zdmc+')] opacity-30"></div>
    
    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <!-- Text Content -->
            <div class="text-center lg:text-left">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/80 backdrop-blur-sm border border-primary-200 shadow-sm mb-6">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-500 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-primary-500"></span>
                    </span>
                    <span class="text-xs font-semibold text-neutral-700 uppercase tracking-wider">Agendamentos abertos</span>
                </div>

                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-neutral-900 leading-tight mb-6">
                    Beleza <span class="text-brand-gradient">autoral</span> com <span class="text-brand-gradient">tecnologia</span>
                </h1>

                <p class="text-lg sm:text-xl text-neutral-600 leading-relaxed mb-8 max-w-2xl mx-auto lg:mx-0">
                    Transforme seu visual com a arte da maquiagem profissional. Atendimento personalizado, produtos premium e resultados surpreendentes.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    @auth
                        <a 
                            href="{{ route('agendamentos.create') }}" 
                            class="group inline-flex items-center justify-center gap-2 px-8 py-4 text-base font-semibold text-white bg-gradient-to-r from-primary-500 to-primary-400 rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300"
                        >
                            <span>Agendar Atendimento</span>
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </a>
                    @else
                        <a 
                            href="{{ route('login') }}" 
                            class="group inline-flex items-center justify-center gap-2 px-8 py-4 text-base font-semibold text-white bg-gradient-to-r from-primary-500 to-primary-400 rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300"
                        >
                            <span>Fazer Login para Agendar</span>
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </a>
                    @endauth

                    <a 
                        href="{{ route('servicos.index') }}" 
                        class="inline-flex items-center justify-center gap-2 px-8 py-4 text-base font-semibold text-neutral-700 bg-white border-2 border-primary-300 rounded-xl hover:bg-primary-50 hover:border-primary-400 transition-all duration-300"
                    >
                        <span>Ver Serviços</span>
                    </a>
                </div>

                <!-- Social Proof -->
                <div class="mt-12 flex items-center gap-8 justify-center lg:justify-start">
                    <div class="flex -space-x-3">
                        @php
                            $avaliacoesPublicadas = \App\Models\Avaliacao::with('cliente')->where('publicado', true)->get();
                            $iniciaisReais = $avaliacoesPublicadas->map(fn($av) => strtoupper(substr($av->cliente->name, 0, 1)))->toArray();
                            // Adiciona avatares genéricos se necessário para completar 4
                            $iniciaisExtras = ['C', 'L'];
                            $todasIniciais = array_merge($iniciaisReais, array_slice($iniciaisExtras, 0, max(0, 4 - count($iniciaisReais))));
                        @endphp
                        @foreach(array_slice($todasIniciais, 0, 4) as $inicial)
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary-400 to-primary-500 border-2 border-white flex items-center justify-center text-white text-xs font-bold">
                            {{ $inicial }}
                        </div>
                        @endforeach
                    </div>
                    <div class="text-left">
                        <p class="text-sm font-semibold text-neutral-900">{{ $totalClientes }}+ clientes atendidas</p>
                        <p class="text-xs text-neutral-500">Avaliação média: {{ $mediaAvaliacoes > 0 ? $mediaAvaliacoes : 'Em breve' }} {{ $mediaAvaliacoes > 0 ? '⭐' : '' }}</p>
                    </div>
                </div>
            </div>

            <!-- Image/Visual Element -->
            <div class="relative lg:block hidden">
                <div class="relative w-full aspect-square rounded-3xl bg-gradient-to-br from-primary-200 via-primary-300 to-accent-200 shadow-2xl overflow-hidden">
                    <!-- Imagem da Profissional -->
                    <img 
                        src="{{ asset('images/chris_hero.png') }}" 
                        alt="Chris Pincel Mágico - Profissional de Beleza" 
                        class="absolute inset-0 w-full h-full object-cover object-center"
                    />
                    
                    <!-- Overlay sutil para melhorar legibilidade do card -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent"></div>
                </div>

                <!-- Floating Card -->
                <div class="absolute -bottom-6 -left-6 bg-white rounded-2xl shadow-xl p-6 max-w-xs z-10">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-primary-500 to-primary-400 flex items-center justify-center text-white font-bold">
                            CP
                        </div>
                        <div>
                            <p class="font-semibold text-neutral-900">Agendamento Rápido</p>
                            <p class="text-sm text-neutral-500">Em apenas 2 minutos</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-20 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl sm:text-4xl font-bold text-neutral-900 mb-4">
                Por que escolher <span class="text-brand-gradient">Chris Pincel Mágico</span>?
            </h2>
            <p class="text-lg text-neutral-600 max-w-2xl mx-auto">
                Experiência completa de beleza com atendimento profissional e tecnologia de ponta
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="group relative bg-gradient-to-br from-white to-primary-50 rounded-2xl p-8 border border-primary-100 hover:border-primary-300 hover:shadow-xl transition-all duration-300">
                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-primary-500 to-primary-400 flex items-center justify-center mb-6 shadow-lg group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-neutral-900 mb-3">Técnicas Exclusivas</h3>
                <p class="text-neutral-600 leading-relaxed">
                    Métodos inovadores de aplicação combinados com produtos premium para resultados duradouros e impecáveis.
                </p>
            </div>

            <!-- Feature 2 -->
            <div class="group relative bg-gradient-to-br from-white to-accent-50 rounded-2xl p-8 border border-accent-200 hover:border-accent-300 hover:shadow-xl transition-all duration-300">
                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-accent-500 to-accent-400 flex items-center justify-center mb-6 shadow-lg group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-neutral-900 mb-3">Agendamento Inteligente</h3>
                <p class="text-neutral-600 leading-relaxed">
                    Sistema automatizado de agendamento com confirmação por WhatsApp e lembretes para sua comodidade.
                </p>
            </div>

            <!-- Feature 3 -->
            <div class="group relative bg-gradient-to-br from-white to-primary-50 rounded-2xl p-8 border border-primary-100 hover:border-primary-300 hover:shadow-xl transition-all duration-300">
                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-primary-500 to-primary-400 flex items-center justify-center mb-6 shadow-lg group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-neutral-900 mb-3">Programa de Fidelidade</h3>
                <p class="text-neutral-600 leading-relaxed">
                    Acumule pontos a cada atendimento e troque por descontos e serviços gratuitos. Quanto mais você vem, mais economiza!
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-20 bg-gradient-to-b from-neutral-50 to-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
            <!-- Stat 1 -->
            <div class="group bg-white rounded-2xl p-8 border-2 border-neutral-100 hover:border-primary-300 hover:shadow-xl transition-all duration-300">
                <div class="flex flex-col items-center text-center">
                    <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-primary-400 to-primary-500 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                        </svg>
                    </div>
                    <p class="text-5xl font-bold text-neutral-900 mb-2">{{ $totalServicos }}+</p>
                    <p class="text-neutral-600 font-medium">Serviços Disponíveis</p>
                </div>
            </div>

            <!-- Stat 2 -->
            <div class="group bg-white rounded-2xl p-8 border-2 border-neutral-100 hover:border-accent-300 hover:shadow-xl transition-all duration-300">
                <div class="flex flex-col items-center text-center">
                    <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-primary-400 to-primary-500 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <p class="text-5xl font-bold text-neutral-900 mb-2">{{ $totalClientes }}+</p>
                    <p class="text-neutral-600 font-medium">Clientes Atendidas</p>
                </div>
            </div>

            <!-- Stat 3 -->
            <div class="group bg-white rounded-2xl p-8 border-2 border-neutral-100 hover:border-primary-300 hover:shadow-xl transition-all duration-300">
                <div class="flex flex-col items-center text-center">
                    <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-primary-400 to-primary-500 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <p class="text-5xl font-bold text-neutral-900 mb-2">2m</p>
                    <p class="text-neutral-600 font-medium">Tempo de Agendamento</p>
                </div>
            </div>

            <!-- Stat 4 -->
            <div class="group bg-white rounded-2xl p-8 border-2 border-neutral-100 hover:border-accent-300 hover:shadow-xl transition-all duration-300">
                <div class="flex flex-col items-center text-center">
                    <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-primary-400 to-primary-500 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                    </div>
                    <p class="text-5xl font-bold text-neutral-900 mb-2">
                        @if($mediaAvaliacoes > 0)
                            {{ $mediaAvaliacoes }}
                        @else
                            --
                        @endif
                    </p>
                    <p class="text-neutral-600 font-medium">Avaliação Média</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Services Preview -->
<section class="py-20 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl sm:text-4xl font-bold text-neutral-900 mb-4">
                Nossos <span class="text-brand-gradient">Serviços</span>
            </h2>
            <p class="text-lg text-neutral-600 max-w-2xl mx-auto mb-8">
                Confira alguns dos nossos serviços mais procurados
            </p>
            <a href="{{ route('servicos.index') }}" class="inline-flex items-center gap-2 text-primary-600 font-semibold hover:gap-3 transition-all">
                <span>Ver todos os serviços</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </a>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($servicos as $servico)
            <div class="group bg-white rounded-2xl overflow-hidden border border-primary-100 hover:border-primary-300 hover:shadow-xl transition-all duration-300">
                <div class="h-48 bg-gradient-to-br from-primary-200 to-accent-200 flex items-center justify-center">
                    @if($servico->imagem_capa)
                        <img src="{{ asset('storage/' . $servico->imagem_capa) }}" alt="{{ $servico->nome }}" class="w-full h-full object-cover">
                    @else
                        <svg class="w-16 h-16 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                        </svg>
                    @endif
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-neutral-900 mb-2">{{ $servico->nome }}</h3>
                    <p class="text-neutral-600 mb-4">{{ Str::limit($servico->descricao, 80) }}</p>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-2xl font-bold text-brand-gradient">R$ {{ number_format($servico->preco, 2, ',', '.') }}</span>
                        <span class="text-sm text-neutral-500">{{ $servico->duracao_minutos }}min</span>
                    </div>
                    @auth
                        <a href="{{ route('agendamentos.create', ['servico' => $servico->id]) }}" class="block w-full text-center px-4 py-2 bg-primary-100 text-primary-700 rounded-lg font-medium hover:bg-primary-200 transition-colors">
                            Agendar
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="block w-full text-center px-4 py-2 bg-primary-100 text-primary-700 rounded-lg font-medium hover:bg-primary-200 transition-colors">
                            Login para Agendar
                        </a>
                    @endauth
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12">
                <p class="text-neutral-600">Nenhum serviço disponível no momento.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-br from-primary-50 via-accent-50 to-primary-100">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl sm:text-4xl font-bold text-neutral-900 mb-6">
            Pronta para sua <span class="text-brand-gradient">transformação</span>?
        </h2>
        <p class="text-lg text-neutral-600 mb-8 max-w-2xl mx-auto">
            Agende seu horário agora e descubra o poder de realçar sua beleza natural com técnicas profissionais
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            @auth
                <a 
                    href="{{ route('agendamentos.create') }}" 
                    class="inline-flex items-center justify-center gap-2 px-8 py-4 text-base font-semibold text-white bg-gradient-to-r from-primary-500 to-primary-400 rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all"
                >
                    <span>Fazer Agendamento</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </a>
            @else
                <a 
                    href="{{ route('login') }}" 
                    class="inline-flex items-center justify-center gap-2 px-8 py-4 text-base font-semibold text-white bg-gradient-to-r from-primary-500 to-primary-400 rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all"
                >
                    <span>Login para Agendar</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </a>
            @endauth
            
            <a 
                href="{{ whatsapp_link('Olá! Gostaria de tirar uma dúvida sobre os serviços.') }}" 
                target="_blank"
                class="inline-flex items-center justify-center gap-2 px-8 py-4 text-base font-semibold text-neutral-700 bg-white border-2 border-primary-300 rounded-xl hover:bg-primary-50 transition-all"
            >
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
                <span>Falar no WhatsApp</span>
            </a>
        </div>
    </div>
</section>
@endsection
