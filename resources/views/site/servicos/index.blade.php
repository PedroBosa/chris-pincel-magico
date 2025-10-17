@extends('layouts.site')

@section('title', 'Catálogo de serviços | Chris Pincel Mágico')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-white to-primary-50 py-12">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <span class="inline-block px-4 py-2 rounded-full bg-primary-100 border border-primary-200 text-xs font-semibold uppercase tracking-wider text-primary-700 mb-4">
                Nossos Serviços
            </span>
            <h1 class="text-4xl sm:text-5xl font-bold text-neutral-900 mb-4">
                Escolha a <span class="text-brand-gradient">produção perfeita</span> para cada ocasião
            </h1>
            <p class="text-lg text-neutral-600 max-w-3xl mx-auto mb-8">
                Todas as opções incluem preparação completa da pele, produtos hipoalergênicos e acabamento duradouro. Consulte também valores diferenciados para retoque.
            </p>
            <a 
                href="{{ route('agendamentos.create') }}" 
                class="inline-flex items-center gap-2 px-8 py-3 bg-gradient-to-r from-primary-500 to-primary-400 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>Agendar Horário</span>
            </a>
        </div>

        <!-- Services Grid -->
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse ($servicos as $servico)
                <article class="group bg-white rounded-2xl overflow-hidden border border-primary-100 hover:border-primary-300 hover:shadow-2xl transition-all duration-300">
                    <!-- Image -->
                    <div class="h-48 bg-gradient-to-br from-primary-200 via-primary-300 to-accent-200 flex items-center justify-center relative overflow-hidden">
                        @if($servico->imagem_capa)
                            <img src="{{ asset('storage/' . $servico->imagem_capa) }}" alt="{{ $servico->nome }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        @else
                            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiNCNTdCN0IiIGZpbGwtb3BhY2l0eT0iMC4xIj48cGF0aCBkPSJNMzYgMTRjMy4zMSAwIDYtMi42OSA2LTZzLTIuNjktNi02LTYtNiAyLjY5LTYgNiAyLjY5IDYgNiA2em0tMTIgMGMzLjMxIDAgNi0yLjY5IDYtNnMtMi42OS02LTYtNi02IDIuNjktNiA2IDIuNjkgNiA2IDZ6Ii8+PC9nPjwvZz48L3N2Zz4=')] opacity-30"></div>
                            <svg class="w-20 h-20 text-white/60 relative z-10 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                            </svg>
                        @endif
                        <!-- Duration Badge -->
                        <div class="absolute top-4 right-4 px-3 py-1.5 rounded-full bg-white/90 backdrop-blur-sm border border-primary-200 shadow-md">
                            <span class="text-xs font-semibold text-neutral-700">{{ $servico->duracao_minutos }} min</span>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-neutral-900 mb-3 group-hover:text-primary-600 transition-colors">
                            <a href="{{ route('servicos.show', $servico) }}">{{ $servico->nome }}</a>
                        </h2>
                        <p class="text-sm text-neutral-600 leading-relaxed mb-6">
                            {{ \Illuminate\Support\Str::limit($servico->descricao ?? 'Transforme seu visual com técnicas profissionais e produtos premium.', 120) }}
                        </p>

                        <!-- Pricing -->
                        <div class="flex items-end justify-between mb-4">
                            <div>
                                <p class="text-xs uppercase tracking-wider text-neutral-500 mb-1">Investimento</p>
                                <p class="text-2xl font-bold text-brand-gradient">
                                    R$ {{ number_format($servico->preco, 2, ',', '.') }}
                                </p>
                                @if ($servico->preco_retoque)
                                    <p class="text-xs text-neutral-500 mt-1">
                                        Retoque: <span class="font-semibold text-primary-600">R$ {{ number_format($servico->preco_retoque, 2, ',', '.') }}</span>
                                    </p>
                                @endif
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-2">
                            <a 
                                href="{{ route('servicos.show', $servico) }}" 
                                class="flex-1 text-center px-4 py-2.5 bg-primary-50 text-primary-700 rounded-lg font-medium hover:bg-primary-100 transition-colors"
                            >
                                Ver Detalhes
                            </a>
                            <a 
                                href="{{ route('agendamentos.create', ['servico' => $servico->id]) }}" 
                                class="flex-1 text-center px-4 py-2.5 bg-gradient-to-r from-primary-500 to-primary-400 text-white rounded-lg font-medium hover:shadow-lg transition-all"
                            >
                                Agendar
                            </a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full text-center py-16">
                    <div class="inline-flex h-16 w-16 items-center justify-center rounded-full bg-primary-100 mb-4">
                        <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-neutral-900 mb-2">Nenhum serviço disponível</h3>
                    <p class="text-neutral-600">Volte em breve para conferir nossas novidades!</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($servicos->hasPages())
            <div class="mt-12">
                {{ $servicos->links() }}
            </div>
        @endif

        <!-- CTA Section -->
        <div class="mt-16 bg-gradient-to-br from-primary-50 to-accent-50 rounded-3xl p-12 text-center border border-primary-200">
            <h2 class="text-3xl font-bold text-neutral-900 mb-4">
                Não encontrou o que procurava?
            </h2>
            <p class="text-lg text-neutral-600 mb-6 max-w-2xl mx-auto">
                Entre em contato conosco pelo WhatsApp e faremos um atendimento personalizado para suas necessidades
            </p>
            <a 
                href="https://wa.me/5585987654321" 
                target="_blank"
                class="inline-flex items-center gap-2 px-8 py-3 bg-gradient-to-r from-accent-500 to-accent-400 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all"
            >
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
                <span>Falar no WhatsApp</span>
            </a>
        </div>
    </div>
</div>
@endsection
