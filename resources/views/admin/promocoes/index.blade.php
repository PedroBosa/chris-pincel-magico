@extends('layouts.admin')

@section('page-title', 'Promoções')
@section('page-description', 'Gerencie promoções e cupons de desconto')

@section('content')
<div class="space-y-6">
    <!-- Header com Botão Criar -->
    <div class="flex items-center justify-between">
        <div></div>
        <button 
            onclick="openModal('criar-promocao')"
            class="inline-flex items-center gap-2 px-6 py-3 bg-primary-500 hover:bg-primary-600 text-white rounded-lg font-medium transition-colors shadow-sm"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>Nova Promoção</span>
        </button>
    </div>

    <!-- Grid de Promoções -->
    @if($promocoes->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($promocoes as $promocao)
                <div class="bg-white rounded-xl shadow-sm border border-neutral-200 overflow-hidden hover:shadow-md transition-shadow">
                    <!-- Header com Badge -->
                    <div class="bg-gradient-to-br from-primary-500 to-primary-400 p-6 text-white relative">
                        <div class="absolute top-4 right-4">
                            @if($promocao->ativo)
                                <span class="px-3 py-1 bg-emerald-500 text-white text-xs font-semibold rounded-full">
                                    Ativo
                                </span>
                            @else
                                <span class="px-3 py-1 bg-neutral-500 text-white text-xs font-semibold rounded-full">
                                    Inativo
                                </span>
                            @endif
                        </div>
                        
                        <div class="mb-2">
                            <h3 class="text-xl font-bold mb-1">{{ $promocao->titulo }}</h3>
                            @if($promocao->codigo_cupom)
                                <div class="inline-flex items-center gap-2 px-3 py-1 bg-white/20 backdrop-blur-sm rounded-lg text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                    <code class="font-mono font-bold">{{ $promocao->codigo_cupom }}</code>
                                </div>
                            @endif
                        </div>

                        <!-- Valor do Desconto -->
                        <div class="text-4xl font-bold">
                            @if($promocao->tipo === 'PERCENTUAL')
                                {{ $promocao->percentual_desconto }}%
                            @else
                                R$ {{ number_format($promocao->valor_desconto, 2, ',', '.') }}
                            @endif
                        </div>
                        <div class="text-sm text-primary-100">
                            {{ $promocao->tipo === 'PERCENTUAL' ? 'de desconto' : 'off' }}
                        </div>
                    </div>

                    <!-- Conteúdo -->
                    <div class="p-6">
                        @if($promocao->descricao)
                            <p class="text-sm text-neutral-600 mb-4 line-clamp-2">
                                {{ $promocao->descricao }}
                            </p>
                        @endif

                        <!-- Informações -->
                        <div class="space-y-2 mb-4">
                            @if($promocao->inicio_vigencia || $promocao->fim_vigencia)
                                <div class="flex items-center gap-2 text-sm text-neutral-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span>
                                        @if($promocao->inicio_vigencia)
                                            {{ $promocao->inicio_vigencia->format('d/m/Y') }}
                                        @else
                                            Sem início
                                        @endif
                                        -
                                        @if($promocao->fim_vigencia)
                                            {{ $promocao->fim_vigencia->format('d/m/Y') }}
                                        @else
                                            Sem fim
                                        @endif
                                    </span>
                                </div>
                            @endif

                            @if($promocao->limite_uso)
                                <div class="flex items-center gap-2 text-sm text-neutral-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    <span>
                                        {{ $promocao->usos_realizados }}/{{ $promocao->limite_uso }} usos
                                    </span>
                                </div>

                                <!-- Barra de Progresso -->
                                @php
                                    $percentage = $promocao->limite_uso > 0 ? ($promocao->usos_realizados / $promocao->limite_uso) * 100 : 0;
                                @endphp
                                <div class="w-full bg-neutral-200 rounded-full h-2">
                                    <div class="bg-primary-500 h-2 rounded-full transition-all" style="width: {{ min($percentage, 100) }}%"></div>
                                </div>
                            @endif
                        </div>

                        <!-- Ações -->
                        <div class="flex items-center justify-center gap-2">
                            <button 
                                onclick="openModal('ver-{{ $promocao->id }}')"
                                class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                title="Ver Detalhes"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                            <button 
                                onclick="openModal('editar-{{ $promocao->id }}')"
                                class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors"
                                title="Editar"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            <button 
                                onclick="openModal('deletar-{{ $promocao->id }}')"
                                class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                title="Excluir"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Modal de Ver Detalhes -->
                <x-modal id="ver-{{ $promocao->id }}" title="Detalhes da Promoção" size="2xl">
                    <div class="space-y-6">
                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 mb-2">Status</label>
                            @if($promocao->ativo)
                                <span class="inline-flex px-3 py-1 bg-emerald-100 text-emerald-700 text-sm font-medium rounded-full">
                                    ✓ Ativo
                                </span>
                            @else
                                <span class="inline-flex px-3 py-1 bg-neutral-200 text-neutral-700 text-sm font-medium rounded-full">
                                    Inativo
                                </span>
                            @endif
                        </div>

                        <!-- Informações Básicas -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 mb-1">Título</label>
                                <p class="text-neutral-900">{{ $promocao->titulo }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 mb-1">Slug</label>
                                <p class="text-neutral-900 font-mono text-sm">{{ $promocao->slug }}</p>
                            </div>
                        </div>

                        @if($promocao->descricao)
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 mb-1">Descrição</label>
                            <p class="text-neutral-900">{{ $promocao->descricao }}</p>
                        </div>
                        @endif

                        <!-- Tipo e Desconto -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 mb-1">Tipo</label>
                                <p class="text-neutral-900">{{ $promocao->tipo === 'PERCENTUAL' ? 'Percentual' : 'Valor Fixo' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 mb-1">Desconto</label>
                                <p class="text-2xl font-bold text-primary-600">
                                    @if($promocao->tipo === 'PERCENTUAL')
                                        {{ $promocao->percentual_desconto }}%
                                    @else
                                        R$ {{ number_format($promocao->valor_desconto, 2, ',', '.') }}
                                    @endif
                                </p>
                            </div>
                        </div>

                        @if($promocao->codigo_cupom)
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 mb-1">Código do Cupom</label>
                            <div class="inline-flex items-center gap-2 px-4 py-2 bg-primary-50 border border-primary-200 rounded-lg">
                                <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                                <code class="font-mono font-bold text-primary-900">{{ $promocao->codigo_cupom }}</code>
                            </div>
                        </div>
                        @endif

                        <!-- Vigência -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 mb-1">Início</label>
                                <p class="text-neutral-900">
                                    {{ $promocao->inicio_vigencia ? $promocao->inicio_vigencia->format('d/m/Y H:i') : 'Sem data de início' }}
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 mb-1">Fim</label>
                                <p class="text-neutral-900">
                                    {{ $promocao->fim_vigencia ? $promocao->fim_vigencia->format('d/m/Y H:i') : 'Sem data de fim' }}
                                </p>
                            </div>
                        </div>

                        <!-- Limite de Uso -->
                        @if($promocao->limite_uso)
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 mb-2">Uso do Cupom</label>
                            <div class="flex items-center gap-4">
                                <div class="flex-1">
                                    @php
                                        $percentage = $promocao->limite_uso > 0 ? ($promocao->usos_realizados / $promocao->limite_uso) * 100 : 0;
                                    @endphp
                                    <div class="w-full bg-neutral-200 rounded-full h-3">
                                        <div class="bg-primary-500 h-3 rounded-full transition-all" style="width: {{ min($percentage, 100) }}%"></div>
                                    </div>
                                </div>
                                <div class="text-sm font-medium text-neutral-700">
                                    {{ $promocao->usos_realizados }} / {{ $promocao->limite_uso }}
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </x-modal>

                <!-- Modal de Editar -->
                <x-modal id="editar-{{ $promocao->id }}" title="Editar Promoção" size="3xl">
                    <form method="POST" action="{{ route('admin.promocoes.update', $promocao) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="space-y-5">
                            <!-- Título e Slug -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-neutral-700 mb-2">
                                        Título <span class="text-red-500">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        name="titulo" 
                                        value="{{ $promocao->titulo }}"
                                        class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                        required
                                    >
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-neutral-700 mb-2">
                                        Slug <span class="text-red-500">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        name="slug" 
                                        value="{{ $promocao->slug }}"
                                        class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                        required
                                    >
                                </div>
                            </div>

                            <!-- Descrição -->
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 mb-2">Descrição</label>
                                <textarea 
                                    name="descricao" 
                                    rows="2"
                                    class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                >{{ $promocao->descricao }}</textarea>
                            </div>

                            <!-- Tipo e Valor -->
                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-neutral-700 mb-2">Tipo <span class="text-red-500">*</span></label>
                                    <select 
                                        name="tipo"
                                        class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                        onchange="toggleDescontoFields('edit', '{{ $promocao->id }}')"
                                        required
                                    >
                                        <option value="PERCENTUAL" {{ $promocao->tipo === 'PERCENTUAL' ? 'selected' : '' }}>Percentual (%)</option>
                                        <option value="VALOR" {{ $promocao->tipo === 'VALOR' ? 'selected' : '' }}>Valor Fixo (R$)</option>
                                    </select>
                                </div>
                                <div id="edit_percentual_field_{{ $promocao->id }}" class="{{ $promocao->tipo === 'PERCENTUAL' ? '' : 'hidden' }}">
                                    <label class="block text-sm font-medium text-neutral-700 mb-2">Desconto (%)</label>
                                    <input 
                                        type="number" 
                                        name="percentual_desconto" 
                                        value="{{ $promocao->percentual_desconto }}"
                                        min="0" 
                                        max="100"
                                        class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                    >
                                </div>
                                <div id="edit_valor_field_{{ $promocao->id }}" class="{{ $promocao->tipo === 'VALOR' ? '' : 'hidden' }}">
                                    <label class="block text-sm font-medium text-neutral-700 mb-2">Desconto (R$)</label>
                                    <input 
                                        type="number" 
                                        name="valor_desconto" 
                                        value="{{ $promocao->valor_desconto }}"
                                        min="0" 
                                        step="0.01"
                                        class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                    >
                                </div>
                            </div>

                            <!-- Código do Cupom -->
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 mb-2">Código do Cupom</label>
                                <input 
                                    type="text" 
                                    name="codigo_cupom" 
                                    value="{{ $promocao->codigo_cupom }}"
                                    class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent font-mono uppercase"
                                    placeholder="Ex: VERAO2025"
                                >
                            </div>

                            <!-- Vigência -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-neutral-700 mb-2">Início da Vigência</label>
                                    <input 
                                        type="datetime-local" 
                                        name="inicio_vigencia" 
                                        value="{{ $promocao->inicio_vigencia?->format('Y-m-d\TH:i') }}"
                                        class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                    >
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-neutral-700 mb-2">Fim da Vigência</label>
                                    <input 
                                        type="datetime-local" 
                                        name="fim_vigencia" 
                                        value="{{ $promocao->fim_vigencia?->format('Y-m-d\TH:i') }}"
                                        class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                    >
                                </div>
                            </div>

                            <!-- Limite e Status -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-neutral-700 mb-2">Limite de Usos</label>
                                    <input 
                                        type="number" 
                                        name="limite_uso" 
                                        value="{{ $promocao->limite_uso }}"
                                        min="0"
                                        class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                        placeholder="Ilimitado"
                                    >
                                    <p class="mt-1 text-xs text-neutral-500">Já utilizado: {{ $promocao->usos_realizados }} vezes</p>
                                </div>
                                <div class="flex items-center">
                                    <label class="flex items-center gap-3 cursor-pointer">
                                        <input 
                                            type="checkbox" 
                                            name="ativo" 
                                            value="1"
                                            {{ $promocao->ativo ? 'checked' : '' }}
                                            class="w-5 h-5 text-primary-600 border-neutral-300 rounded focus:ring-primary-500"
                                        >
                                        <span class="text-sm font-medium text-neutral-700">Promoção ativa</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Botões -->
                        <div class="flex items-center justify-end gap-3 mt-8 pt-5 border-t border-neutral-200">
                            <button 
                                type="button" 
                                onclick="closeModal('editar-{{ $promocao->id }}')"
                                class="px-5 py-2.5 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 rounded-lg font-medium transition-colors"
                            >
                                Cancelar
                            </button>
                            <button 
                                type="submit"
                                class="px-6 py-2.5 bg-primary-500 hover:bg-primary-600 text-white rounded-lg font-medium transition-colors inline-flex items-center gap-2"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Salvar Alterações
                            </button>
                        </div>
                    </form>
                </x-modal>

                <!-- Modal de Deletar -->
                <x-modal id="deletar-{{ $promocao->id }}" title="Confirmar Exclusão" size="md">
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
                                <div><span class="font-semibold">Promoção:</span> {{ $promocao->titulo }}</div>
                                @if($promocao->codigo_cupom)
                                    <div><span class="font-semibold">Cupom:</span> <code class="bg-neutral-200 px-2 py-0.5 rounded font-mono text-xs">{{ $promocao->codigo_cupom }}</code></div>
                                @endif
                                <div>
                                    <span class="font-semibold">Desconto:</span> 
                                    @if($promocao->tipo === 'PERCENTUAL')
                                        {{ $promocao->percentual_desconto }}%
                                    @else
                                        R$ {{ number_format($promocao->valor_desconto, 2, ',', '.') }}
                                    @endif
                                </div>
                                @if($promocao->usos_realizados > 0)
                                    <div class="text-amber-600"><span class="font-semibold">⚠️ Usos realizados:</span> {{ $promocao->usos_realizados }} vez(es)</div>
                                @endif
                            </div>
                        </div>

                        <p class="text-sm text-neutral-600">
                            Tem certeza que deseja excluir esta promoção?
                        </p>
                    </div>

                    <x-slot name="footer">
                        <button 
                            type="button"
                            onclick="closeModal('deletar-{{ $promocao->id }}')"
                            class="px-4 py-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 rounded-lg font-medium transition-colors"
                        >
                            Cancelar
                        </button>
                        <form method="POST" action="{{ route('admin.promocoes.destroy', $promocao) }}" class="inline">
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

        <!-- Paginação -->
        @if($promocoes->hasPages())
            <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-6">
                {{ $promocoes->links() }}
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-12 text-center">
            <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-neutral-900 mb-2">
                Nenhuma promoção cadastrada
            </h3>
            <p class="text-neutral-600 mb-6">
                Crie sua primeira promoção para atrair mais clientes
            </p>
            <button 
                onclick="openModal('criar-promocao')"
                class="inline-flex items-center gap-2 px-6 py-3 bg-primary-500 hover:bg-primary-600 text-white rounded-lg font-medium transition-colors"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Nova Promoção</span>
            </button>
        </div>
    @endif
</div>

<!-- Modal de Criar Promoção -->
<x-modal id="criar-promocao" title="Nova Promoção" size="3xl">
    <form method="POST" action="{{ route('admin.promocoes.store') }}" id="form-criar-promocao">
        @csrf
        
        <!-- Erros de Validação -->
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                <ul class="text-sm text-red-600 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <div class="space-y-6">
            <!-- Título e Slug -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="titulo" class="block text-sm font-medium text-neutral-700 mb-2">
                        Título <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="titulo" 
                        name="titulo" 
                        value="{{ old('titulo') }}"
                        class="w-full px-4 py-2 border @error('titulo') border-red-500 @else border-neutral-300 @enderror rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                        required
                    >
                    @error('titulo')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="slug" class="block text-sm font-medium text-neutral-700 mb-2">
                        Slug <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="slug" 
                        name="slug" 
                        value="{{ old('slug') }}"
                        class="w-full px-4 py-2 border @error('slug') border-red-500 @else border-neutral-300 @enderror rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                        required
                    >
                    @error('slug')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @else
                        <p class="mt-1 text-xs text-neutral-500">Gerado automaticamente do título</p>
                    @enderror
                </div>
            </div>

            <!-- Descrição -->
            <div>
                <label for="descricao" class="block text-sm font-medium text-neutral-700 mb-2">
                    Descrição
                </label>
                <textarea 
                    id="descricao" 
                    name="descricao" 
                    rows="3"
                    class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                >{{ old('descricao') }}</textarea>
            </div>

            <!-- Tipo e Valor -->
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label for="tipo" class="block text-sm font-medium text-neutral-700 mb-2">
                        Tipo <span class="text-red-500">*</span>
                    </label>
                    <select 
                        id="tipo" 
                        name="tipo"
                        class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                        onchange="toggleDescontoFields('create', '')"
                        required
                    >
                        <option value="PERCENTUAL" {{ old('tipo') === 'PERCENTUAL' ? 'selected' : '' }}>Percentual (%)</option>
                        <option value="VALOR" {{ old('tipo') === 'VALOR' ? 'selected' : '' }}>Valor Fixo (R$)</option>
                    </select>
                </div>
                <div id="create_percentual_field" class="{{ old('tipo', 'PERCENTUAL') === 'PERCENTUAL' ? '' : 'hidden' }}">
                    <label for="percentual_desconto" class="block text-sm font-medium text-neutral-700 mb-2">
                        Desconto (%) <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="number" 
                        id="percentual_desconto" 
                        name="percentual_desconto" 
                        value="{{ old('percentual_desconto', 10) }}"
                        min="0" 
                        max="100"
                        class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                    >
                </div>
                <div id="create_valor_field" class="{{ old('tipo', 'PERCENTUAL') === 'VALOR' ? '' : 'hidden' }}">
                    <label for="valor_desconto" class="block text-sm font-medium text-neutral-700 mb-2">
                        Desconto (R$) <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="number" 
                        id="valor_desconto" 
                        name="valor_desconto" 
                        value="{{ old('valor_desconto', 10) }}"
                        min="0" 
                        step="0.01"
                        class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                    >
                </div>
            </div>

            <!-- Código do Cupom -->
            <div>
                <label for="codigo_cupom" class="block text-sm font-medium text-neutral-700 mb-2">
                    Código do Cupom
                </label>
                <input 
                    type="text" 
                    id="codigo_cupom" 
                    name="codigo_cupom" 
                    value="{{ old('codigo_cupom') }}"
                    class="w-full px-4 py-2 border @error('codigo_cupom') border-red-500 @else border-neutral-300 @enderror rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent font-mono uppercase"
                    placeholder="Ex: VERAO2025"
                    maxlength="40"
                >
                @error('codigo_cupom')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @else
                    <p class="mt-1 text-sm text-neutral-500">Deixe em branco para promoção sem cupom (desconto automático)</p>
                @enderror
            </div>

            <!-- Vigência -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="inicio_vigencia" class="block text-sm font-medium text-neutral-700 mb-2">
                        Início da Vigência
                    </label>
                    <input 
                        type="datetime-local" 
                        id="inicio_vigencia" 
                        name="inicio_vigencia" 
                        value="{{ old('inicio_vigencia') }}"
                        class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                    >
                </div>
                <div>
                    <label for="fim_vigencia" class="block text-sm font-medium text-neutral-700 mb-2">
                        Fim da Vigência
                    </label>
                    <input 
                        type="datetime-local" 
                        id="fim_vigencia" 
                        name="fim_vigencia" 
                        value="{{ old('fim_vigencia') }}"
                        class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                    >
                </div>
            </div>

            <!-- Limite de Uso -->
            <div>
                <label for="limite_uso" class="block text-sm font-medium text-neutral-700 mb-2">
                    Limite de Usos
                </label>
                <input 
                    type="number" 
                    id="limite_uso" 
                    name="limite_uso" 
                    value="{{ old('limite_uso') }}"
                    min="0"
                    class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                    placeholder="Deixe em branco para ilimitado"
                >
                <p class="mt-1 text-sm text-neutral-500">Quantas vezes o cupom pode ser usado no total</p>
            </div>

            <!-- Ativo -->
            <div class="flex items-center gap-3">
                <input 
                    type="checkbox" 
                    id="ativo" 
                    name="ativo" 
                    value="1"
                    {{ old('ativo', true) ? 'checked' : '' }}
                    class="w-4 h-4 text-primary-600 border-neutral-300 rounded focus:ring-primary-500"
                >
                <label for="ativo" class="text-sm font-medium text-neutral-700">
                    Promoção ativa
                </label>
            </div>
        </div>

        <!-- Botões fixos na parte inferior -->
        <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t border-neutral-200 bg-white -mx-6 -mb-6 px-6 pb-6 sticky bottom-0">
            <button 
                type="button" 
                onclick="closeModal('criar-promocao')"
                class="px-4 py-2 bg-neutral-200 hover:bg-neutral-300 text-neutral-700 rounded-lg font-medium transition-colors"
            >
                Cancelar
            </button>
            <button 
                type="submit"
                class="px-6 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg font-medium transition-colors shadow-sm"
            >
                <svg class="w-5 h-5 inline-block mr-2 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Criar Promoção
            </button>
        </div>
    </form>
</x-modal>

<script>
function toggleDescontoFields(mode, id) {
    const suffix = id ? `_${id}` : '';
    const selectId = mode === 'create' ? 'tipo' : `${mode}_tipo_${id}`;
    const selectedType = document.getElementById(selectId).value;
    
    const percentualField = document.getElementById(`${mode}_percentual_field${suffix}`);
    const valorField = document.getElementById(`${mode}_valor_field${suffix}`);
    
    if (selectedType === 'PERCENTUAL') {
        percentualField.classList.remove('hidden');
        valorField.classList.add('hidden');
    } else {
        percentualField.classList.add('hidden');
        valorField.classList.remove('hidden');
    }
}

// Gerar slug automaticamente a partir do título
document.addEventListener('DOMContentLoaded', function() {
    const tituloInput = document.getElementById('titulo');
    const slugInput = document.getElementById('slug');
    
    if (tituloInput && slugInput) {
        let slugManualmenteEditado = false;
        
        slugInput.addEventListener('input', function() {
            slugManualmenteEditado = slugInput.value.length > 0;
        });
        
        tituloInput.addEventListener('input', function() {
            if (!slugManualmenteEditado) {
                const slug = tituloInput.value
                    .toLowerCase()
                    .normalize('NFD')
                    .replace(/[\u0300-\u036f]/g, '') // Remove acentos
                    .replace(/[^a-z0-9]+/g, '-') // Substitui caracteres especiais por hífen
                    .replace(/^-+|-+$/g, ''); // Remove hífens do início e fim
                
                slugInput.value = slug;
            }
        });
    }
    
    // Transformar código do cupom em maiúsculas automaticamente
    const codigoCupomInput = document.getElementById('codigo_cupom');
    if (codigoCupomInput) {
        codigoCupomInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
        });
    }
});

// Abre o modal automaticamente se houver erros de validação
@if ($errors->any())
    document.addEventListener('DOMContentLoaded', function() {
        openModal('criar-promocao');
    });
@endif
</script>
@endsection
