@extends('layouts.admin')

@section('title', 'Serviços')
@section('page-title', 'Serviços')
@section('page-description', 'Gerencie todos os serviços oferecidos')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div class="flex items-center gap-4">
        <div class="relative">
            <input 
                type="search" 
                placeholder="Buscar serviço..." 
                class="pl-10 pr-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
            >
            <svg class="w-5 h-5 text-neutral-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
        
        <select class="px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
            <option value="">Todos</option>
            <option value="1">Ativos</option>
            <option value="0">Inativos</option>
        </select>
    </div>

    <button 
        type="button"
        onclick="openModal('criar-servico')"
        class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-primary-500 to-primary-400 text-white rounded-lg font-semibold hover:shadow-lg transition-all"
    >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
        <span>Novo Serviço</span>
    </button>
</div>

<div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3 mb-6">
    @foreach ($servicos as $servico)
    <div class="bg-white rounded-2xl border border-neutral-200 shadow-sm hover:shadow-lg transition-shadow overflow-hidden group">
        <div class="h-32 bg-gradient-to-br from-primary-200 to-accent-200 flex items-center justify-center">
            @if($servico->imagem_capa)
                <img src="{{ asset('storage/' . $servico->imagem_capa) }}" alt="{{ $servico->nome }}" class="w-full h-full object-cover">
            @else
                <svg class="w-12 h-12 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                </svg>
            @endif
        </div>
        
        <div class="p-6">
            <div class="flex items-start justify-between mb-3">
                <h3 class="text-lg font-bold text-neutral-900">{{ $servico->nome }}</h3>
                @if($servico->ativo)
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">
                        Ativo
                    </span>
                @else
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-700">
                        Inativo
                    </span>
                @endif
            </div>
            
            <p class="text-sm text-neutral-600 mb-4 line-clamp-2">{{ $servico->descricao ?? 'Sem descrição' }}</p>
            
            <div class="flex items-center justify-between mb-4 pb-4 border-b border-neutral-100">
                <div>
                    <p class="text-xs text-neutral-500 mb-1">Duração</p>
                    <p class="text-sm font-semibold text-neutral-900">{{ $servico->duracao_minutos }} min</p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-neutral-500 mb-1">Preço</p>
                    <p class="text-lg font-bold text-primary-600">R$ {{ number_format($servico->preco, 0, ',', '.') }}</p>
                </div>
            </div>
            
            <div class="flex gap-2">
                <!-- Ver -->
                <button 
                    type="button"
                    onclick="openModal('servico-{{ $servico->id }}')"
                    class="flex-1 p-2 text-primary-600 hover:text-primary-700 hover:bg-primary-50 rounded-lg transition-colors flex items-center justify-center"
                    title="Ver Detalhes"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>

                <!-- Editar -->
                <button 
                    type="button"
                    onclick="openModal('editar-servico-{{ $servico->id }}')"
                    class="flex-1 p-2 text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-colors flex items-center justify-center"
                    title="Editar"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </button>

                <!-- Deletar -->
                <button 
                    type="button"
                    onclick="openModal('deletar-servico-{{ $servico->id }}')"
                    class="flex-1 p-2 text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors flex items-center justify-center"
                    title="Deletar"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Modal para cada serviço -->
    <x-modal id="servico-{{ $servico->id }}" title="{{ $servico->nome }}" size="2xl">
        <div class="space-y-6">
            <!-- Imagem ou Ícone -->
            <div class="h-48 bg-gradient-to-br from-primary-200 to-accent-200 rounded-xl flex items-center justify-center overflow-hidden">
                @if($servico->imagem)
                    <img src="{{ asset('storage/' . $servico->imagem) }}" alt="{{ $servico->nome }}" class="w-full h-full object-cover">
                @else
                    <svg class="w-20 h-20 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                    </svg>
                @endif
            </div>

            <!-- Status Badge -->
            <div class="flex items-center justify-between">
                <div>
                    @if($servico->ativo)
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Serviço Ativo
                        </span>
                    @else
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-gray-100 text-gray-700 border border-gray-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Serviço Inativo
                        </span>
                    @endif
                </div>
                <div class="text-right">
                    <div class="text-sm text-neutral-600 mb-1">ID do Serviço</div>
                    <div class="text-lg font-bold text-neutral-900">#{{ $servico->id }}</div>
                </div>
            </div>

            <!-- Descrição -->
            @if($servico->descricao)
                <div class="p-4 bg-neutral-50 rounded-xl">
                    <h5 class="font-bold text-neutral-900 mb-2 flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                        </svg>
                        Descrição
                    </h5>
                    <p class="text-sm text-neutral-700 leading-relaxed">{{ $servico->descricao }}</p>
                </div>
            @endif

            <!-- Informações de Preço e Duração -->
            <div class="grid grid-cols-2 gap-4">
                <div class="p-4 bg-primary-50 rounded-xl border border-primary-100">
                    <div class="text-xs font-semibold text-primary-600 uppercase tracking-wider mb-2">Preço do Serviço</div>
                    <div class="text-3xl font-bold text-primary-600">R$ {{ number_format($servico->preco, 0, ',', '.') }}</div>
                </div>
                <div class="p-4 bg-accent-50 rounded-xl border border-accent-100">
                    <div class="text-xs font-semibold text-accent-600 uppercase tracking-wider mb-2">Duração</div>
                    <div class="text-3xl font-bold text-accent-600">{{ $servico->duracao_minutos }}</div>
                    <div class="text-xs text-accent-600 font-medium">minutos</div>
                </div>
            </div>

            <!-- Preço Retoque (se disponível) -->
            @if($servico->preco_retoque)
                <div class="p-4 bg-amber-50 rounded-xl border border-amber-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm font-bold text-neutral-900 mb-1">Preço de Retoque</div>
                            <div class="text-xs text-neutral-600">Valor especial para retoques</div>
                        </div>
                        <div class="text-2xl font-bold text-amber-600">R$ {{ number_format($servico->preco_retoque, 0, ',', '.') }}</div>
                    </div>
                </div>
            @endif

            <!-- Estatísticas -->
            <div class="p-4 bg-neutral-50 rounded-xl">
                <h5 class="font-bold text-neutral-900 mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Estatísticas
                </h5>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <div class="text-xs text-neutral-600 mb-1">Total de Agendamentos</div>
                        <div class="text-2xl font-bold text-neutral-900">{{ $servico->agendamentos_count ?? 0 }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-neutral-600 mb-1">Cadastrado em</div>
                        <div class="text-sm font-medium text-neutral-900">{{ $servico->created_at->format('d/m/Y') }}</div>
                        <div class="text-xs text-neutral-500">{{ $servico->created_at->diffForHumans() }}</div>
                    </div>
                </div>
            </div>
        </div>

        <x-slot name="footer">
            <button 
                type="button"
                onclick="closeModal('servico-{{ $servico->id }}')"
                class="px-4 py-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 rounded-lg font-medium transition-colors"
            >
                Fechar
            </button>
        </x-slot>
    </x-modal>

    <!-- Modal de Editar Serviço -->
    <x-modal id="editar-servico-{{ $servico->id }}" title="Editar Serviço" size="2xl">
        <form method="POST" action="{{ route('admin.servicos.update', $servico) }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- Imagem de Capa -->
            <div>
                <label class="block text-sm font-semibold text-neutral-700 mb-2">
                    Imagem de Capa
                </label>
                <div class="flex items-center gap-4">
                    <div id="preview-editar-{{ $servico->id }}" class="w-32 h-32 border-2 border-dashed border-neutral-300 rounded-lg flex items-center justify-center overflow-hidden bg-neutral-50">
                        @if($servico->imagem_capa)
                            <img src="{{ asset('storage/' . $servico->imagem_capa) }}" class="w-full h-full object-cover">
                        @else
                            <svg class="w-12 h-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        @endif
                    </div>
                    <div class="flex-1">
                        <input 
                            type="file" 
                            id="imagem_capa_{{ $servico->id }}" 
                            name="imagem_capa" 
                            accept="image/*"
                            onchange="previewImage(this, 'preview-editar-{{ $servico->id }}')"
                            class="block w-full text-sm text-neutral-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-600 hover:file:bg-primary-100"
                        >
                        <p class="mt-1 text-xs text-neutral-500">PNG, JPG ou WEBP até 2MB</p>
                        @if($servico->imagem_capa)
                            <p class="mt-1 text-xs text-blue-600">Deixe em branco para manter a imagem atual</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Nome -->
            <div>
                <label for="nome_{{ $servico->id }}" class="block text-sm font-semibold text-neutral-700 mb-2">
                    Nome do Serviço *
                </label>
                <input 
                    type="text" 
                    id="nome_{{ $servico->id }}" 
                    name="nome" 
                    value="{{ $servico->nome }}"
                    required
                    class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                >
            </div>

            <!-- Slug -->
            <div>
                <label for="slug_{{ $servico->id }}" class="block text-sm font-semibold text-neutral-700 mb-2">
                    Slug (URL amigável) *
                </label>
                <input 
                    type="text" 
                    id="slug_{{ $servico->id }}" 
                    name="slug" 
                    value="{{ $servico->slug }}"
                    required
                    class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                >
            </div>

            <!-- Descrição -->
            <div>
                <label for="descricao_{{ $servico->id }}" class="block text-sm font-semibold text-neutral-700 mb-2">
                    Descrição
                </label>
                <textarea 
                    id="descricao_{{ $servico->id }}" 
                    name="descricao" 
                    rows="3"
                    class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                >{{ $servico->descricao }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <!-- Duração -->
                <div>
                    <label for="duracao_minutos_{{ $servico->id }}" class="block text-sm font-semibold text-neutral-700 mb-2">
                        Duração (minutos) *
                    </label>
                    <input 
                        type="number" 
                        id="duracao_minutos_{{ $servico->id }}" 
                        name="duracao_minutos" 
                        value="{{ $servico->duracao_minutos }}"
                        required
                        min="1"
                        class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                    >
                </div>

                <!-- Preço -->
                <div>
                    <label for="preco_{{ $servico->id }}" class="block text-sm font-semibold text-neutral-700 mb-2">
                        Preço (R$) *
                    </label>
                    <input 
                        type="number" 
                        id="preco_{{ $servico->id }}" 
                        name="preco" 
                        value="{{ $servico->preco }}"
                        required
                        min="0"
                        step="0.01"
                        class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                    >
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <!-- Preço Retoque -->
                <div>
                    <label for="preco_retoque_{{ $servico->id }}" class="block text-sm font-semibold text-neutral-700 mb-2">
                        Preço Retoque (R$)
                    </label>
                    <input 
                        type="number" 
                        id="preco_retoque_{{ $servico->id }}" 
                        name="preco_retoque" 
                        value="{{ $servico->preco_retoque }}"
                        min="0"
                        step="0.01"
                        class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                    >
                </div>

                <!-- Dias para Retoque -->
                <div>
                    <label for="dias_para_retoque_{{ $servico->id }}" class="block text-sm font-semibold text-neutral-700 mb-2">
                        Dias para Retoque *
                    </label>
                    <input 
                        type="number" 
                        id="dias_para_retoque_{{ $servico->id }}" 
                        name="dias_para_retoque" 
                        value="{{ $servico->dias_para_retoque }}"
                        required
                        min="0"
                        class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                    >
                </div>
            </div>

            <!-- Ativo -->
            <div class="flex items-center gap-3 p-4 bg-neutral-50 rounded-lg">
                <input 
                    type="checkbox" 
                    id="ativo_{{ $servico->id }}" 
                    name="ativo" 
                    value="1"
                    {{ $servico->ativo ? 'checked' : '' }}
                    class="w-5 h-5 text-primary-600 border-neutral-300 rounded focus:ring-2 focus:ring-primary-500"
                >
                <label for="ativo_{{ $servico->id }}" class="text-sm font-medium text-neutral-700">
                    Serviço ativo e disponível para agendamento
                </label>
            </div>

            <div class="flex items-center gap-3 pt-4">
                <button 
                    type="button"
                    onclick="closeModal('editar-servico-{{ $servico->id }}')"
                    class="px-4 py-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 rounded-lg font-medium transition-colors"
                >
                    Cancelar
                </button>
                <button 
                    type="submit"
                    class="flex-1 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium transition-colors"
                >
                    Salvar Alterações
                </button>
            </div>
        </form>
    </x-modal>

    <!-- Modal de Deletar Serviço -->
    <x-modal id="deletar-servico-{{ $servico->id }}" title="Confirmar Exclusão" size="md">
        <div class="space-y-4">
            <div class="flex items-center gap-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <h4 class="font-bold text-neutral-900 mb-1">Atenção!</h4>
                    <p class="text-sm text-neutral-700">Esta ação não pode ser desfeita.</p>
                </div>
            </div>

            <div class="p-4 bg-neutral-50 rounded-lg">
                <p class="text-sm text-neutral-700 mb-2">Você está prestes a excluir:</p>
                <div class="space-y-1 text-sm">
                    <div><span class="font-semibold">Serviço:</span> {{ $servico->nome }}</div>
                    <div><span class="font-semibold">Preço:</span> R$ {{ number_format($servico->preco, 2, ',', '.') }}</div>
                    <div><span class="font-semibold">Duração:</span> {{ $servico->duracao_minutos }} minutos</div>
                </div>
            </div>

            <p class="text-sm text-neutral-600">
                Tem certeza que deseja excluir este serviço?
            </p>
        </div>

        <x-slot name="footer">
            <button 
                type="button"
                onclick="closeModal('deletar-servico-{{ $servico->id }}')"
                class="px-4 py-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 rounded-lg font-medium transition-colors"
            >
                Cancelar
            </button>
            <form method="POST" action="{{ route('admin.servicos.destroy', $servico) }}" class="inline">
                @csrf
                @method('DELETE')
                <button 
                    type="submit"
                    class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg font-medium transition-colors inline-flex items-center gap-2"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Sim, Excluir
                </button>
            </form>
        </x-slot>
    </x-modal>
    @endforeach
</div>

@if ($servicos->isEmpty())
<div class="bg-white rounded-2xl border border-neutral-200 shadow-sm p-16 text-center">
    <div class="w-20 h-20 mx-auto bg-neutral-100 rounded-full flex items-center justify-center mb-6">
        <svg class="w-10 h-10 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
        </svg>
    </div>
    <h3 class="text-xl font-bold text-neutral-900 mb-2">Nenhum Serviço Cadastrado</h3>
    <p class="text-neutral-600 mb-6">Comece adicionando seu primeiro serviço</p>
    <a href="{{ route('admin.servicos.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-primary-500 to-primary-400 text-white rounded-lg font-semibold hover:shadow-lg transition-all">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
        <span>Adicionar Primeiro Serviço</span>
    </a>
</div>
@endif

@if($servicos->hasPages())
    <div class="mt-6">
        {{ $servicos->links() }}
    </div>
@endif

<!-- Modal de Criar Serviço -->
<x-modal id="criar-servico" title="Novo Serviço" size="2xl">
    <form method="POST" action="{{ route('admin.servicos.store') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf

        <!-- Imagem de Capa -->
        <div>
            <label class="block text-sm font-semibold text-neutral-700 mb-2">
                Imagem de Capa
            </label>
            <div class="flex items-center gap-4">
                <div id="preview-criar" class="w-32 h-32 border-2 border-dashed border-neutral-300 rounded-lg flex items-center justify-center overflow-hidden bg-neutral-50">
                    <svg class="w-12 h-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <input 
                        type="file" 
                        id="imagem_capa" 
                        name="imagem_capa" 
                        accept="image/*"
                        onchange="previewImage(this, 'preview-criar')"
                        class="block w-full text-sm text-neutral-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-600 hover:file:bg-primary-100"
                    >
                    <p class="mt-1 text-xs text-neutral-500">PNG, JPG ou WEBP até 2MB</p>
                </div>
            </div>
        </div>

        <!-- Nome -->
        <div>
            <label for="nome_criar" class="block text-sm font-semibold text-neutral-700 mb-2">
                Nome do Serviço *
            </label>
            <input 
                type="text" 
                id="nome_criar" 
                name="nome" 
                required
                class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
            >
        </div>

        <!-- Slug -->
        <div>
            <label for="slug_criar" class="block text-sm font-semibold text-neutral-700 mb-2">
                Slug (URL amigável) *
            </label>
            <input 
                type="text" 
                id="slug_criar" 
                name="slug" 
                required
                placeholder="exemplo-servico"
                class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
            >
            <p class="mt-1 text-xs text-neutral-500">Usado na URL do serviço</p>
        </div>

        <!-- Descrição -->
        <div>
            <label for="descricao_criar" class="block text-sm font-semibold text-neutral-700 mb-2">
                Descrição
            </label>
            <textarea 
                id="descricao_criar" 
                name="descricao" 
                rows="3"
                class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
            ></textarea>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <!-- Duração -->
            <div>
                <label for="duracao_minutos_criar" class="block text-sm font-semibold text-neutral-700 mb-2">
                    Duração (minutos) *
                </label>
                <input 
                    type="number" 
                    id="duracao_minutos_criar" 
                    name="duracao_minutos" 
                    required
                    min="15"
                    value="60"
                    class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                >
            </div>

            <!-- Preço -->
            <div>
                <label for="preco_criar" class="block text-sm font-semibold text-neutral-700 mb-2">
                    Preço (R$) *
                </label>
                <input 
                    type="number" 
                    id="preco_criar" 
                    name="preco" 
                    required
                    min="0"
                    step="0.01"
                    class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                >
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <!-- Preço Retoque -->
            <div>
                <label for="preco_retoque_criar" class="block text-sm font-semibold text-neutral-700 mb-2">
                    Preço Retoque (R$)
                </label>
                <input 
                    type="number" 
                    id="preco_retoque_criar" 
                    name="preco_retoque" 
                    min="0"
                    step="0.01"
                    class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                >
            </div>

            <!-- Dias para Retoque -->
            <div>
                <label for="dias_para_retoque_criar" class="block text-sm font-semibold text-neutral-700 mb-2">
                    Dias para Retoque *
                </label>
                <input 
                    type="number" 
                    id="dias_para_retoque_criar" 
                    name="dias_para_retoque" 
                    required
                    min="0"
                    value="30"
                    class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                >
            </div>
        </div>

        <!-- Ativo -->
        <div class="flex items-center gap-3 p-4 bg-neutral-50 rounded-lg">
            <input 
                type="checkbox" 
                id="ativo_criar" 
                name="ativo" 
                value="1"
                checked
                class="w-5 h-5 text-primary-600 border-neutral-300 rounded focus:ring-2 focus:ring-primary-500"
            >
            <label for="ativo_criar" class="text-sm font-medium text-neutral-700">
                Serviço ativo e disponível para agendamento
            </label>
        </div>

        <div class="flex items-center gap-3 pt-4">
            <button 
                type="button"
                onclick="closeModal('criar-servico')"
                class="px-4 py-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 rounded-lg font-medium transition-colors"
            >
                Cancelar
            </button>
            <button 
                type="submit"
                class="flex-1 px-4 py-2 bg-gradient-to-r from-primary-500 to-primary-400 text-white rounded-lg font-semibold transition-all hover:shadow-lg"
            >
                Criar Serviço
            </button>
        </div>
    </form>
</x-modal>

<script>
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    const file = input.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endsection
