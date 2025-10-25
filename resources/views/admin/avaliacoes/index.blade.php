@extends('layouts.admin')

@section('page-title', 'Avaliações')
@section('page-description', 'Gerencie e modere as avaliações dos clientes')

@section('content')
<div class="space-y-6">
    <!-- Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-6">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-semibold text-neutral-500 uppercase tracking-wider">Total</h3>
                <div class="w-10 h-10 rounded-lg bg-neutral-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-neutral-900">{{ $estatisticas['total'] }}</p>
            <p class="text-xs text-neutral-500 mt-1">avaliações recebidas</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-emerald-200 p-6">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-semibold text-emerald-600 uppercase tracking-wider">Publicadas</h3>
                <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-emerald-600">{{ $estatisticas['publicadas'] }}</p>
            <p class="text-xs text-neutral-500 mt-1">visíveis ao público</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-amber-200 p-6">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-semibold text-amber-600 uppercase tracking-wider">Pendentes</h3>
                <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-amber-600">{{ $estatisticas['pendentes'] }}</p>
            <p class="text-xs text-neutral-500 mt-1">aguardando moderação</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-primary-200 p-6">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-semibold text-primary-600 uppercase tracking-wider">Média</h3>
                <div class="w-10 h-10 rounded-lg bg-primary-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-primary-600">{{ $estatisticas['media'] }}</p>
            <p class="text-xs text-neutral-500 mt-1">de 5 estrelas</p>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-6">
        <form method="GET" action="{{ route('admin.avaliacoes.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Busca -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-neutral-700 mb-2">Buscar</label>
                <input 
                    type="text" 
                    name="busca" 
                    value="{{ request('busca') }}"
                    placeholder="Nome do cliente ou comentário..."
                    class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                >
            </div>

            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-2">Status</label>
                <select 
                    name="status"
                    class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                >
                    <option value="">Todos</option>
                    <option value="publicado" {{ request('status') === 'publicado' ? 'selected' : '' }}>Publicadas</option>
                    <option value="pendente" {{ request('status') === 'pendente' ? 'selected' : '' }}>Pendentes</option>
                </select>
            </div>

            <!-- Nota -->
            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-2">Nota</label>
                <select 
                    name="nota"
                    class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                >
                    <option value="">Todas</option>
                    <option value="5" {{ request('nota') == '5' ? 'selected' : '' }}>⭐⭐⭐⭐⭐ (5)</option>
                    <option value="4" {{ request('nota') == '4' ? 'selected' : '' }}>⭐⭐⭐⭐ (4)</option>
                    <option value="3" {{ request('nota') == '3' ? 'selected' : '' }}>⭐⭐⭐ (3)</option>
                    <option value="2" {{ request('nota') == '2' ? 'selected' : '' }}>⭐⭐ (2)</option>
                    <option value="1" {{ request('nota') == '1' ? 'selected' : '' }}>⭐ (1)</option>
                </select>
            </div>

            <div class="md:col-span-4 flex gap-3">
                <button 
                    type="submit"
                    class="px-6 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg font-medium transition-colors"
                >
                    Filtrar
                </button>
                <a 
                    href="{{ route('admin.avaliacoes.index') }}"
                    class="px-6 py-2 bg-neutral-200 hover:bg-neutral-300 text-neutral-700 rounded-lg font-medium transition-colors"
                >
                    Limpar
                </a>
            </div>
        </form>
    </div>

    <!-- Lista de Avaliações -->
    @if($avaliacoes->count() > 0)
        <div class="space-y-4">
            @foreach($avaliacoes as $avaliacao)
                <div class="bg-white rounded-xl shadow-sm border border-neutral-200 overflow-hidden hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex flex-col gap-6 md:grid md:grid-cols-[minmax(0,1fr)_11rem] md:gap-6 md:items-start">
                            <!-- Cliente e conteúdo -->
                            <div class="flex flex-col gap-4">
                                <div class="flex items-start gap-4">
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-primary-500 to-primary-400 flex items-center justify-center text-white font-bold text-lg flex-shrink-0">
                                        {{ strtoupper(substr($avaliacao->cliente->name ?? 'A', 0, 1)) }}
                                    </div>
                                    <div class="flex-1 space-y-3">
                                        <div class="flex flex-wrap items-center gap-3">
                                            <h3 class="text-lg font-bold text-neutral-900">
                                                {{ $avaliacao->cliente->name ?? 'Cliente removido' }}
                                            </h3>
                                            @if($avaliacao->publicado)
                                                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-full">
                                                    Publicada
                                                </span>
                                            @else
                                                <span class="px-3 py-1 bg-amber-100 text-amber-700 text-xs font-semibold rounded-full">
                                                    Pendente
                                                </span>
                                            @endif
                                        </div>

                                        <div class="flex flex-wrap items-center gap-2 text-sm text-neutral-600">
                                            <div class="flex items-center gap-2">
                                                <div class="flex gap-0.5">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <svg class="w-5 h-5 {{ $i <= $avaliacao->nota ? 'text-yellow-400' : 'text-neutral-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                        </svg>
                                                    @endfor
                                                </div>
                                                <span class="font-semibold">{{ $avaliacao->nota }}.0</span>
                                            </div>
                                            <span class="hidden md:inline">·</span>
                                            <span><span class="font-medium">Serviço:</span> {{ $avaliacao->agendamento->servico->nome ?? 'Serviço removido' }}</span>
                                        </div>
                                    </div>
                                </div>

                                @if($avaliacao->comentario)
                                    <div class="bg-neutral-50 rounded-lg p-4 border border-neutral-200">
                                        <p class="text-neutral-700 leading-relaxed">{{ $avaliacao->comentario }}</p>
                                    </div>
                                @else
                                    <p class="text-neutral-400 italic">Sem comentário</p>
                                @endif

                                <p class="text-xs text-neutral-500">
                                    Avaliado em {{ $avaliacao->created_at->format('d/m/Y \às H:i') }}
                                    @if($avaliacao->publicado && $avaliacao->publicado_em)
                                        · Publicado em {{ $avaliacao->publicado_em->format('d/m/Y') }}
                                    @endif
                                </p>
                            </div>

                            <!-- Ações -->
                            <div class="flex flex-col gap-2 border-t border-neutral-200 pt-4 md:border-t-0 md:border-l md:border-neutral-200 md:pt-0 md:pl-4">
                                @if(!$avaliacao->publicado)
                                    <form method="POST" action="{{ route('admin.avaliacoes.update', $avaliacao) }}" class="w-full">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="publicado" value="1">
                                        <button 
                                            type="submit"
                                            class="w-full px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg text-sm font-semibold transition-colors text-center"
                                            title="Publicar avaliação"
                                        >
                                            Publicar
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admin.avaliacoes.update', $avaliacao) }}" class="w-full">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="publicado" value="0">
                                        <button 
                                            type="submit"
                                            class="w-full px-4 py-2 bg-white text-amber-600 border border-amber-500 hover:bg-amber-50 hover:text-amber-700 rounded-lg text-sm font-semibold transition-colors text-center"
                                            title="Despublicar avaliação"
                                        >
                                            Despublicar
                                        </button>
                                    </form>
                                @endif

                                <form 
                                    method="POST" 
                                    action="{{ route('admin.avaliacoes.destroy', $avaliacao) }}"
                                    onsubmit="return confirm('Tem certeza que deseja excluir esta avaliação?')"
                                    class="w-full"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button 
                                        type="submit"
                                        class="w-full px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg text-sm font-semibold transition-colors text-center"
                                        title="Excluir avaliação"
                                    >
                                        Excluir
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginação -->
        <div class="mt-6">
            {{ $avaliacoes->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-16 text-center">
            <div class="w-20 h-20 bg-neutral-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-neutral-900 mb-2">Nenhuma avaliação encontrada</h3>
            <p class="text-neutral-600 mb-6">
                @if(request()->hasAny(['busca', 'status', 'nota']))
                    Tente ajustar os filtros para encontrar avaliações.
                @else
                    Ainda não há avaliações cadastradas no sistema.
                @endif
            </p>
            @if(request()->hasAny(['busca', 'status', 'nota']))
                <a 
                    href="{{ route('admin.avaliacoes.index') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-primary-500 hover:bg-primary-600 text-white font-semibold rounded-lg transition-colors"
                >
                    <span>Limpar Filtros</span>
                </a>
            @endif
        </div>
    @endif
</div>

@if(session('success'))
<div 
    x-data="{ show: true }" 
    x-show="show" 
    x-init="setTimeout(() => show = false, 3000)"
    class="fixed bottom-4 right-4 bg-emerald-500 text-white px-6 py-4 rounded-lg shadow-lg z-50"
>
    {{ session('success') }}
</div>
@endif
@endsection
