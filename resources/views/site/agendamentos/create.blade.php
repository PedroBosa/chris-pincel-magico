@extends('layouts.site')

@section('title', 'Agendar atendimento | Chris Pincel Mágico')

@section('content')
@php
    $resumoFidelidade = $resumoFidelidade ?? [];
    $carregamentoServicosErro = $carregamentoServicosErro ?? false;
@endphp
<div class="min-h-screen bg-gradient-to-br from-white via-primary-50 to-accent-50 py-12 overflow-x-hidden">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white border border-primary-200 shadow-sm mb-4">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-500 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-primary-500"></span>
                </span>
                <span class="text-xs font-semibold text-neutral-700 uppercase tracking-wider">Agendamento Online</span>
            </div>
            <h1 class="text-4xl sm:text-5xl font-bold text-neutral-900 mb-4">
                Reserve seu <span class="text-brand-gradient">horário ideal</span>
            </h1>
            <p class="text-lg text-neutral-600 max-w-2xl mx-auto">
                Processo simples e rápido. Escolha o serviço, data e horário. Confirmação imediata!
            </p>
        </div>

    <div class="grid grid-cols-1 lg:grid-cols-[minmax(0,1fr),400px] gap-8 items-start">
            <!-- Form Section -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 lg:p-10 border border-primary-100 shadow-xl">
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-neutral-900 mb-2">Informações do Agendamento</h2>
                    <p class="text-sm text-neutral-600">Preencha os campos abaixo para continuar</p>
                </div>

                <form method="POST" action="{{ route('agendamentos.store') }}" class="space-y-8">
                    @csrf

                    <!-- Erros Gerais -->
                    @if($errors->has('geral'))
                        <div class="bg-red-50 border-2 border-red-200 rounded-xl p-4">
                            <div class="flex items-start gap-3">
                                <div class="w-6 h-6 bg-red-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-red-700 font-medium">{{ $errors->first('geral') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-50 border-2 border-red-200 rounded-xl p-4">
                            <div class="flex items-start gap-3">
                                <div class="w-6 h-6 bg-red-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Service Selection -->
                    <div class="space-y-3">
                        <label for="servico" class="flex items-center gap-2 text-sm font-semibold text-neutral-700">
                            <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                            </svg>
                            <span>Escolha o Serviço</span>
                        </label>
                        @if($carregamentoServicosErro)
                            <div class="bg-amber-50 border-2 border-amber-200 rounded-xl p-4 flex items-start gap-3">
                                <div class="w-6 h-6 bg-amber-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-amber-800 font-medium">
                                        Não foi possível carregar os serviços no momento. Tente novamente mais tarde ou entre em contato pelo WhatsApp.
                                    </p>
                                </div>
                            </div>
                        @endif
                        <div class="relative">
                            <select 
                                id="servico" 
                                name="servico_id" 
                                required 
                                class="w-full px-5 py-4 border-2 border-primary-200 rounded-xl bg-white text-neutral-900 font-medium focus:border-primary-500 focus:ring-4 focus:ring-primary-100 transition-all appearance-none @error('servico_id') border-red-400 @enderror"
                            >
                                <option value="" disabled selected>Selecione o serviço desejado...</option>
                                @foreach ($servicos as $servico)
                                    <option 
                                        value="{{ $servico->id }}" 
                                        data-preco="{{ $servico->preco }}"
                                        @selected(old('servico_id') == $servico->id)
                                    >
                                        {{ $servico->nome }} • {{ $servico->duracao_minutos }}min • R$ {{ number_format($servico->preco, 2, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-primary-500">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                        @error('servico_id')
                            <p class="text-sm text-red-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="text-xs text-neutral-500 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Não encontrou o que procura? <a href="{{ route('servicos.index') }}" class="text-primary-600 hover:text-primary-700 font-medium">Ver todos os serviços</a>
                        </p>
                    </div>

                    <!-- Date and Time -->
                    <div class="space-y-6">
                        <!-- Date -->
                        <div class="space-y-3">
                            <label for="data" class="flex items-center gap-2 text-sm font-semibold text-neutral-700">
                                <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>Data Desejada</span>
                            </label>
                            <input 
                                type="date" 
                                id="data" 
                                name="data" 
                                value="{{ old('data') }}" 
                                min="{{ date('Y-m-d') }}"
                                required 
                                class="w-full px-5 py-4 border-2 border-primary-200 rounded-xl bg-white text-neutral-900 font-medium focus:border-primary-500 focus:ring-4 focus:ring-primary-100 transition-all @error('data') border-red-400 @enderror"
                            >
                            @error('data')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Horários Disponíveis -->
                        <div id="horarios-container" class="hidden space-y-3">
                            <label class="flex items-center gap-2 text-sm font-semibold text-neutral-700">
                                <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Horários Disponíveis</span>
                                <span id="dia-semana" class="text-xs text-neutral-500 font-normal"></span>
                            </label>
                            
                            <!-- Loading -->
                            <div id="horarios-loading" class="hidden p-8 text-center bg-neutral-50 rounded-xl border-2 border-dashed border-neutral-300">
                                <svg class="w-8 h-8 mx-auto mb-3 text-primary-500 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <p class="text-sm text-neutral-600 font-medium">Buscando horários disponíveis...</p>
                            </div>

                            <!-- Horários Grid -->
                            <div id="horarios-grid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3"></div>

                            <!-- Sem horários -->
                            <div id="sem-horarios" class="hidden p-8 text-center bg-amber-50 rounded-xl border-2 border-amber-200">
                                <svg class="w-12 h-12 mx-auto mb-3 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-sm font-semibold text-amber-900 mb-1">Nenhum horário disponível</p>
                                <p class="text-xs text-amber-700">Tente escolher outra data</p>
                            </div>

                            <!-- Campo hidden para enviar o horário -->
                            <input type="hidden" id="hora" name="hora" required>
                        </div>

                        <!-- Mensagem inicial -->
                        <div id="selecione-data-msg" class="p-6 text-center bg-neutral-50 rounded-xl border-2 border-dashed border-neutral-300">
                            <svg class="w-10 h-10 mx-auto mb-3 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-sm text-neutral-600">Selecione um serviço e uma data para ver os horários disponíveis</p>
                        </div>
                    </div>

                    <!-- Forma de Pagamento -->
                    <div class="space-y-3">
                        <label class="flex items-center gap-2 text-sm font-semibold text-neutral-700">
                            <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                            <span>Forma de Pagamento (Sinal 30%)</span>
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <!-- PIX -->
                            <label class="relative cursor-pointer block">
                                <input type="radio" name="forma_pagamento" value="pix" required checked class="hidden">
                                <div class="payment-card h-full p-5 border-2 border-primary-500 rounded-xl bg-primary-50 shadow-lg transition-all duration-300 hover:border-primary-600 hover:shadow-xl">
                                    <div class="flex flex-col items-center gap-2 text-center">
                                        <div class="w-12 h-12 flex items-center justify-center">
                                            <svg class="w-10 h-10 text-neutral-900" viewBox="0 0 512 512" fill="currentColor">
                                                <path d="M242.4 292.5C247.8 287.1 257.1 287.1 262.5 292.5L339.5 369.5C353.7 383.7 372.6 391.5 392.6 391.5H407.7L310.6 488.6C280.3 518.1 231.1 518.1 200.8 488.6L103.3 391.2H112.6C132.6 391.2 151.5 383.4 165.7 369.2L242.4 292.5zM262.5 218.9C257.1 224.3 247.8 224.3 242.4 218.9L165.7 142.2C151.5 127.1 132.6 120.2 112.6 120.2H103.3L200.7 22.8C231.1-7.6 280.3-7.6 310.6 22.8L407.8 119.9H392.6C372.6 119.9 353.7 127.7 339.5 141.9L262.5 218.9zM112.6 142.7C126.4 142.7 139.1 148.3 149.7 158.1L226.4 234.8C233.6 241.1 243 245.6 252.5 245.6C261.9 245.6 271.3 241.1 278.5 234.8L355.5 157.8C365.3 148.1 378.8 142.5 392.6 142.5H430.3L488.6 200.8C518.9 231.1 518.9 280.3 488.6 310.6L430.3 368.9H392.6C378.8 368.9 365.3 363.3 355.5 353.5L278.5 276.5C264.6 262.6 240.3 262.6 226.4 276.6L149.7 353.2C139.1 363 126.4 368.6 112.6 368.6H80.2L22.8 311.2C-7.6 280.9-7.6 231.7 22.8 201.4L80.2 143.1L112.6 142.7z"/>
                                            </svg>
                                        </div>
                                        <span class="font-bold text-neutral-900 text-base">PIX</span>
                                        <span class="text-xs text-neutral-600">Aprovação instantânea</span>
                                    </div>
                                    <!-- Checkmark quando selecionado - Aesthetic -->
                                    <div class="payment-checkmark absolute top-4 right-3 w-6 h-6 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-full flex items-center justify-center shadow-md">
                                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                </div>
                            </label>

                            <!-- Cartão de Crédito -->
                            <label class="relative cursor-pointer block">
                                <input type="radio" name="forma_pagamento" value="credit_card" class="hidden">
                                <div class="payment-card h-full p-5 border-2 border-neutral-300 rounded-xl bg-white transition-all duration-300 hover:border-primary-400 hover:shadow-md">
                                    <div class="flex flex-col items-center gap-2 text-center">
                                        <div class="w-12 h-12 flex items-center justify-center">
                                            <svg class="w-10 h-10 text-neutral-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                            </svg>
                                        </div>
                                        <span class="font-bold text-neutral-900 text-base">Crédito</span>
                                        <span class="text-xs text-neutral-600">Parcelamento disponível</span>
                                    </div>
                                    <!-- Checkmark quando selecionado - Aesthetic -->
                                    <div class="payment-checkmark absolute top-4 right-3 w-6 h-6 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-full items-center justify-center shadow-md hidden">
                                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                </div>
                            </label>

                            <!-- Cartão de Débito -->
                            <label class="relative cursor-pointer block">
                                <input type="radio" name="forma_pagamento" value="debit_card" class="hidden">
                                <div class="payment-card h-full p-5 border-2 border-neutral-300 rounded-xl bg-white transition-all duration-300 hover:border-primary-400 hover:shadow-md">
                                    <div class="flex flex-col items-center gap-2 text-center">
                                        <div class="w-12 h-12 flex items-center justify-center">
                                            <svg class="w-10 h-10 text-neutral-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                        <span class="font-bold text-neutral-900 text-base">Débito</span>
                                        <span class="text-xs text-neutral-600">À vista</span>
                                    </div>
                                    <!-- Checkmark quando selecionado - Aesthetic -->
                                    <div class="payment-checkmark absolute top-4 right-3 w-6 h-6 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-full items-center justify-center shadow-md hidden">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                </div>
                            </label>
                        </div>
                        @error('forma_pagamento')
                            <p class="text-sm text-red-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="text-xs text-neutral-500 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            É necessário pagar 30% de sinal para confirmar o agendamento. O restante pode ser pago no dia do atendimento.
                        </p>
                    </div>

                    <!-- Cupom de Desconto -->
                    <div class="space-y-3">
                        <label for="codigo_cupom" class="flex items-center gap-2 text-sm font-semibold text-neutral-700">
                            <svg class="w-5 h-5 text-accent-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            <span>Cupom de Desconto</span>
                            <span class="text-xs text-neutral-500 font-normal">(Opcional)</span>
                        </label>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <input 
                                type="text" 
                                id="codigo_cupom" 
                                placeholder="Ex: VERAO2025"
                                class="flex-1 px-5 py-4 border-2 border-primary-200 rounded-xl bg-white text-neutral-900 font-mono uppercase focus:border-primary-500 focus:ring-4 focus:ring-primary-100 transition-all"
                            >
                            <button 
                                type="button"
                                onclick="validarCupom()"
                                id="btn-aplicar-cupom"
                                class="px-6 py-4 bg-gradient-to-r from-accent-500 to-accent-400 text-white font-semibold rounded-xl hover:from-accent-600 hover:to-accent-500 transition-all shadow-md hover:shadow-lg whitespace-nowrap"
                            >
                                Aplicar
                            </button>
                        </div>
                        
                        <div id="cupom-resultado" class="hidden"></div>
                        
                        <!-- Campos hidden para enviar no form -->
                        <input type="hidden" id="promocao_id" name="promocao_id">
                        <input type="hidden" id="codigo_cupom_validado" name="codigo_cupom">
                    </div>

                    <!-- Observações -->
                    <div class="space-y-3">
                        <label for="observacoes" class="flex items-center gap-2 text-sm font-semibold text-neutral-700">
                            <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                            </svg>
                            <span>Observações (Opcional)</span>
                        </label>
                        <textarea 
                            id="observacoes" 
                            name="observacoes" 
                            rows="3"
                            maxlength="500"
                            placeholder="Alguma informação adicional que deseja nos passar?"
                            class="w-full px-5 py-4 border-2 border-primary-200 rounded-xl bg-white text-neutral-900 focus:border-primary-500 focus:ring-4 focus:ring-primary-100 transition-all resize-none @error('observacoes') border-red-400 @enderror"
                        >{{ old('observacoes') }}</textarea>
                        @error('observacoes')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-neutral-500">Máximo 500 caracteres</p>
                    </div>

                    @php
                        $fidelidadeResumo = $resumoFidelidade ?? [];
                        $saldoPontos = $fidelidadeResumo['pontos_atuais'] ?? 0;
                        $valorEquivalente = $fidelidadeResumo['valor_resgate_disponivel'] ?? 0;
                        $resgateMinimo = $fidelidadeResumo['resgate_minimo'] ?? 0;
                        $faltamParaResgate = $fidelidadeResumo['faltam_para_resgate'] ?? 0;
                    @endphp

                    @auth
                        <div class="bg-white rounded-2xl border-2 border-accent-200 p-6 space-y-4 shadow-sm">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div>
                                    <h3 class="text-lg font-bold text-neutral-900 flex items-center gap-2">
                                        <svg class="w-5 h-5 text-accent-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                                        </svg>
                                        Usar pontos de fidelidade
                                    </h3>
                                    <p class="text-sm text-neutral-600">
                                        Saldo disponível: <span class="font-semibold text-accent-600">{{ $saldoPontos }} pts</span>
                                        (≈ R$ {{ number_format($valorEquivalente, 2, ',', '.') }})
                                    </p>
                                </div>
                                <span class="inline-flex items-center gap-1 text-xs font-bold uppercase tracking-wider px-3 py-1 rounded-full bg-accent-100 text-accent-700">
                                    Opcional
                                </span>
                            </div>

                            <div class="space-y-3">
                                <label for="usar_pontos" class="text-sm font-semibold text-neutral-800 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-accent-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                    </svg>
                                    Quantos pontos deseja utilizar agora?
                                </label>
                                <div class="flex flex-col sm:flex-row gap-3">
                                    <input
                                        type="number"
                                        name="usar_pontos"
                                        id="usar_pontos"
                                        min="0"
                                        step="1"
                                        inputmode="numeric"
                                        value="{{ old('usar_pontos', 0) }}"
                                        class="w-full sm:max-w-xs px-4 py-3 border-2 border-accent-200 rounded-xl focus:border-accent-500 focus:ring-4 focus:ring-accent-100 text-neutral-900"
                                        placeholder="Ex.: {{ max($resgateMinimo, 50) }}"
                                        data-resgate-minimo="{{ $resgateMinimo }}"
                                        data-pontos-disponiveis="{{ $saldoPontos }}"
                                    >
                                    <div class="flex items-center gap-2">
                                        <button
                                            type="button"
                                            id="usar-pontos-max"
                                            class="{{ $saldoPontos <= 0 ? 'hidden ' : '' }}px-4 py-2 rounded-xl bg-accent-500 text-white text-sm font-semibold shadow hover:bg-accent-600 transition-all"
                                        >
                                            Usar máximo
                                        </button>
                                        <button
                                            type="button"
                                            id="limpar-pontos"
                                            class="px-4 py-2 rounded-xl border-2 border-neutral-300 text-neutral-700 text-sm font-semibold hover:border-neutral-400 transition-all"
                                        >
                                            Não usar agora
                                        </button>
                                    </div>
                                </div>
                                <p id="usar-pontos-feedback" class="text-xs text-neutral-500">
                                    @if($resgateMinimo > 0)
                                        Resgate mínimo: {{ $resgateMinimo }} pontos (≈ R$ {{ number_format($resgateMinimo * ($fidelidadeResumo['valor_por_ponto'] ?? 0), 2, ',', '.') }}).
                                    @else
                                        Escolha livremente quantos pontos deseja utilizar.
                                    @endif
                                </p>
                                <p id="usar-pontos-alerta" class="hidden text-xs font-semibold text-red-600"></p>
                                @if($saldoPontos < $resgateMinimo && $resgateMinimo > 0)
                                    <p class="text-xs text-neutral-500">
                                        Ainda faltam <span class="font-semibold text-neutral-700">{{ $faltamParaResgate }}</span> pontos para atingir o resgate mínimo. Continue acumulando! ✨
                                    </p>
                                @endif
                                @error('usar_pontos')
                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    @else
                        <div class="bg-white rounded-2xl border-2 border-dashed border-accent-200 p-6 text-sm text-neutral-600">
                            <p class="font-semibold text-neutral-900 mb-1">Programa de fidelidade</p>
                            <p>Acesse sua conta para usar pontos acumulados como desconto imediato nos agendamentos.</p>
                            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 mt-3 text-accent-600 font-semibold hover:text-accent-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7-7l7 7-7 7" />
                                </svg>
                                Entrar agora
                            </a>
                        </div>
                    @endauth

                    <!-- Resumo de Valores -->
                    <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-2xl p-6 border-2 border-emerald-200">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-emerald-700 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
                            </svg>
                            Resumo de Valores
                        </h3>
                        
                        <div id="resumo-valores" class="space-y-3">
                            <!-- Será preenchido dinamicamente -->
                            <div class="text-center py-8">
                                <svg class="w-12 h-12 mx-auto text-emerald-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-sm text-emerald-600">Selecione um serviço para ver os valores</p>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit"
                        class="group w-full flex items-center justify-center gap-3 px-8 py-5 bg-gradient-to-r from-primary-500 to-primary-400 text-white text-lg font-bold rounded-xl shadow-lg hover:shadow-xl hover:scale-[1.02] transition-all duration-300"
                    >
                        <span>Continuar Agendamento</span>
                        <svg class="w-6 h-6 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </button>
                </form>
            </div>

            <!-- Sidebar Info -->
            <div class="space-y-6 mt-8 lg:mt-0">
                <!-- How it Works -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-primary-200 shadow-xl">
                    <h3 class="text-xl font-bold text-neutral-900 mb-6 flex items-center gap-2">
                        <svg class="w-6 h-6 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Como Funciona
                    </h3>
                    <div class="space-y-4">
                        <div class="flex gap-3">
                            <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center flex-shrink-0 font-bold text-sm text-primary-600">1</div>
                            <div>
                                <p class="font-semibold text-neutral-900 mb-1">Escolha e Confirme</p>
                                <p class="text-sm text-neutral-600">Selecione serviço, data e horário desejados</p>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center flex-shrink-0 font-bold text-sm text-primary-600">2</div>
                            <div>
                                <p class="font-semibold text-neutral-900 mb-1">Pagamento do Sinal</p>
                                <p class="text-sm text-neutral-600">30% via PIX para garantir seu horário</p>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center flex-shrink-0 font-bold text-sm text-primary-600">3</div>
                            <div>
                                <p class="font-semibold text-neutral-900 mb-1">Confirmação Automática</p>
                                <p class="text-sm text-neutral-600">Lembrete 24h antes por WhatsApp</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Benefits -->
                <div class="bg-white rounded-2xl p-6 border border-primary-200 shadow-md">
                    <h4 class="font-bold text-neutral-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-accent-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                        </svg>
                        Vantagens
                    </h4>
                    <ul class="space-y-3 text-sm text-neutral-600">
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-primary-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Retoque com desconto em até {{ $servicos->first()->dias_para_retoque ?? 15 }} dias</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-primary-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Cancelamento grátis com 24h de antecedência</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-primary-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Acumule pontos no programa de fidelidade</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-primary-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Produtos premium e hipoalergênicos</span>
                        </li>
                    </ul>
                </div>

                <!-- WhatsApp Help -->
                <div class="bg-gradient-to-br from-accent-50 to-accent-100 rounded-2xl p-6 border border-accent-200">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-accent-500 to-accent-400 flex items-center justify-center flex-shrink-0 shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold text-neutral-900 mb-2">Precisa de Ajuda?</p>
                            <p class="text-sm text-neutral-600 mb-3">
                                Fale conosco para recomendações personalizadas ou dúvidas sobre pacotes especiais
                            </p>
                            <a 
                                href="https://wa.me/5585987654321?text=Olá! Preciso de ajuda para agendar um serviço" 
                                target="_blank"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-accent-500 to-accent-400 text-white text-sm font-semibold rounded-lg hover:shadow-lg transition-all"
                            >
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0012.04 2m.01 1.67c2.2 0 4.26.86 5.82 2.42a8.225 8.225 0 012.41 5.83c0 4.54-3.7 8.23-8.24 8.23-1.48 0-2.93-.39-4.19-1.15l-.3-.17-3.12.82.83-3.04-.2-.32a8.188 8.188 0 01-1.26-4.38c.01-4.54 3.7-8.24 8.25-8.24M8.53 7.33c-.16 0-.43.06-.66.31-.22.25-.87.86-.87 2.07 0 1.22.89 2.39 1 2.56.14.17 1.76 2.67 4.25 3.73.59.27 1.05.42 1.41.53.59.19 1.13.16 1.56.1.48-.07 1.46-.6 1.67-1.18.21-.58.21-1.07.15-1.18-.07-.1-.23-.16-.48-.27l-1.44-.7c-.23-.11-.39-.12-.56.04-.17.16-.64.81-.78.97-.15.17-.29.19-.53.07-.26-.13-1.06-.39-2-1.23-.74-.66-1.23-1.47-1.38-1.7-.14-.24-.02-.37.11-.49.12-.11.24-.29.37-.44.12-.14.17-.25.25-.41.08-.17.04-.31-.02-.43-.06-.11-.56-1.35-.77-1.84-.2-.48-.4-.42-.56-.43-.14 0-.3-.01-.46-.01z"/>
                                </svg>
                                <span>Chamar no WhatsApp</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmação -->
@if(session('agendamento_criado'))
<div id="modal-confirmacao" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 overflow-y-auto">
    <div class="min-h-full flex items-center justify-center p-4 py-8">
    @php
        $agendamento = App\Models\Agendamento::with(['servico', 'usuario'])->find(session('agendamento_criado'));
    @endphp
    
    @if($agendamento)
    <div class="bg-white rounded-2xl lg:rounded-3xl max-w-2xl w-full shadow-2xl animate-fade-in max-h-[90vh] overflow-y-auto custom-scrollbar">
        <!-- Header -->
        <div class="bg-gradient-to-br from-emerald-400 to-emerald-500 p-6 sm:p-8 text-center rounded-t-2xl lg:rounded-t-3xl sticky top-0 z-10">
            <div class="mx-auto w-16 h-16 sm:w-20 sm:h-20 bg-white rounded-full flex items-center justify-center mb-3 sm:mb-4 shadow-lg">
                <svg class="w-8 h-8 sm:w-10 sm:h-10 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h2 class="text-2xl sm:text-3xl font-bold text-white mb-2">Agendamento Confirmado!</h2>
            <p class="text-sm sm:text-base text-emerald-50">Confira os detalhes abaixo</p>
        </div>

        <!-- Body -->
        <div class="p-4 sm:p-6 lg:p-8 space-y-4 sm:space-y-6">
            <!-- Dados do Agendamento -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
                <div class="bg-primary-50 rounded-xl p-3 sm:p-4 text-center border border-primary-200">
                    <p class="text-xs font-bold text-primary-700 uppercase mb-1 sm:mb-2">Serviço</p>
                    <p class="font-bold text-sm sm:text-base text-neutral-900">{{ $agendamento->servico->nome }}</p>
                    <p class="text-xs text-neutral-600 mt-1">{{ $agendamento->servico->duracao_minutos }}min</p>
                </div>
                
                <div class="bg-accent-50 rounded-xl p-3 sm:p-4 text-center border border-accent-200">
                    <p class="text-xs font-bold text-accent-700 uppercase mb-1 sm:mb-2">Data</p>
                    <p class="font-bold text-sm sm:text-base text-neutral-900">{{ $agendamento->data_hora_inicio->format('d/m/Y') }}</p>
                </div>
                
                <div class="bg-primary-50 rounded-xl p-3 sm:p-4 text-center border border-primary-200">
                    <p class="text-xs font-bold text-primary-700 uppercase mb-1 sm:mb-2">Horário</p>
                    <p class="font-bold text-sm sm:text-base text-neutral-900">{{ $agendamento->data_hora_inicio->format('H:i') }}</p>
                </div>
            </div>

            <!-- Valores -->
            <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-xl sm:rounded-2xl p-4 sm:p-6 border-2 border-emerald-200">
                <h3 class="text-xs sm:text-sm font-bold uppercase tracking-wider text-emerald-700 mb-3 sm:mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    Resumo Financeiro
                </h3>
                
                <div class="space-y-2 sm:space-y-3">
                    @if($agendamento->valor_original && $agendamento->valor_desconto > 0)
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-neutral-600">Valor original:</span>
                            <span class="text-neutral-500 line-through">R$ {{ number_format($agendamento->valor_original, 2, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-green-600 font-semibold">Desconto aplicado:</span>
                            <span class="text-green-600 font-bold">- R$ {{ number_format($agendamento->valor_desconto, 2, ',', '.') }}</span>
                        </div>
                    @endif
                    
                    <div class="flex justify-between items-center pt-2 sm:pt-3 border-t-2 border-emerald-300">
                        <span class="font-bold text-sm sm:text-base text-neutral-900">Valor Total:</span>
                        <span class="text-xl sm:text-2xl font-bold text-emerald-700">R$ {{ number_format($agendamento->valor_total, 2, ',', '.') }}</span>
                    </div>
                    
                    <div class="bg-white rounded-lg p-3 sm:p-4 mt-2 sm:mt-3 space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-xs sm:text-sm text-neutral-600">Sinal (30%):</span>
                            <span class="text-base sm:text-lg font-bold text-primary-600">R$ {{ number_format($agendamento->valor_sinal, 2, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-neutral-500">Restante (no dia):</span>
                            <span class="text-xs sm:text-sm text-neutral-700">R$ {{ number_format($agendamento->valor_total - $agendamento->valor_sinal, 2, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Forma de Pagamento -->
            <div class="bg-blue-50 rounded-xl p-3 sm:p-4 border border-blue-200">
                <div class="flex items-center gap-3">
                    @if($agendamento->forma_pagamento_sinal == 'pix')
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-primary-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-primary-600" viewBox="0 0 512 512" fill="currentColor">
                                <path d="M242.4 292.5C247.8 287.1 257.1 287.1 262.5 292.5L339.5 369.5C353.7 383.7 372.6 391.5 392.6 391.5H407.7L310.6 488.6C280.3 518.1 231.1 518.1 200.8 488.6L103.3 391.2H112.6C132.6 391.2 151.5 383.4 165.7 369.2L242.4 292.5zM262.5 218.9C257.1 224.3 247.8 224.3 242.4 218.9L165.7 142.2C151.5 127.1 132.6 120.2 112.6 120.2H103.3L200.7 22.8C231.1-7.6 280.3-7.6 310.6 22.8L407.8 119.9H392.6C372.6 119.9 353.7 127.7 339.5 141.9L262.5 218.9zM112.6 142.7C126.4 142.7 139.1 148.3 149.7 158.1L226.4 234.8C233.6 241.1 243 245.6 252.5 245.6C261.9 245.6 271.3 241.1 278.5 234.8L355.5 157.8C365.3 148.1 378.8 142.5 392.6 142.5H430.3L488.6 200.8C518.9 231.1 518.9 280.3 488.6 310.6L430.3 368.9H392.6C378.8 368.9 365.3 363.3 355.5 353.5L278.5 276.5C264.6 262.6 240.3 262.6 226.4 276.6L149.7 353.2C139.1 363 126.4 368.6 112.6 368.6H80.2L22.8 311.2C-7.6 280.9-7.6 231.7 22.8 201.4L80.2 143.1L112.6 142.7z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold text-sm sm:text-base text-neutral-900">PIX</p>
                            <p class="text-xs text-neutral-600">Pagamento instantâneo</p>
                        </div>
                    @elseif($agendamento->forma_pagamento_sinal == 'credit_card')
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold text-sm sm:text-base text-neutral-900">Cartão de Crédito</p>
                            <p class="text-xs text-neutral-600">Parcelamento disponível</p>
                        </div>
                    @elseif($agendamento->forma_pagamento_sinal == 'debit_card')
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold text-sm sm:text-base text-neutral-900">Cartão de Débito</p>
                            <p class="text-xs text-neutral-600">Pagamento à vista</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Alerta -->
            <div class="bg-amber-50 border-l-4 border-amber-400 rounded-lg p-3 sm:p-4">
                <div class="flex gap-2 sm:gap-3">
                    <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <div>
                        <p class="text-xs sm:text-sm font-bold text-amber-900 mb-1">Importante!</p>
                        <p class="text-xs sm:text-sm text-amber-800">O sinal de 30% deve ser pago em até <strong>2 horas</strong>. Aguarde o contato da equipe via WhatsApp com as instruções de pagamento.</p>
                    </div>
                </div>
            </div>

            <!-- Botões -->
            <div class="flex flex-col sm:flex-row gap-3 pt-2 sm:pt-4 pb-2">
                <a 
                    href="https://wa.me/5585987654321?text=Olá! Acabei de fazer um agendamento ({{ $agendamento->data_hora_inicio->format('d/m/Y') }} às {{ $agendamento->data_hora_inicio->format('H:i') }})" 
                    target="_blank"
                    class="flex-1 inline-flex items-center justify-center gap-2 px-4 sm:px-6 py-3 sm:py-4 bg-gradient-to-r from-accent-500 to-accent-400 text-white rounded-xl font-bold text-sm sm:text-base shadow-lg hover:shadow-xl hover:scale-[1.02] transition-all"
                >
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0012.04 2m.01 1.67c2.2 0 4.26.86 5.82 2.42a8.225 8.225 0 012.41 5.83c0 4.54-3.7 8.23-8.24 8.23-1.48 0-2.93-.39-4.19-1.15l-.3-.17-3.12.82.83-3.04-.2-.32a8.188 8.188 0 01-1.26-4.38c.01-4.54 3.7-8.24 8.25-8.24M8.53 7.33c-.16 0-.43.06-.66.31-.22.25-.87.86-.87 2.07c0 1.22.89 2.39 1 2.56.14.17 1.76 2.67 4.25 3.73.59.27 1.05.42 1.41.53.59.19 1.13.16 1.56.1.48-.07 1.46-.6 1.67-1.18.21-.58.21-1.07.15-1.18-.07-.1-.23-.16-.48-.27l-1.44-.7c-.23-.11-.39-.12-.56.04-.17.16-.64.81-.78.97-.15.17-.29.19-.53.07-.26-.13-1.06-.39-2-1.23-.74-.66-1.23-1.47-1.38-1.7-.14-.24-.02-.37.11-.49.12-.11.24-.29.37-.44.12-.14.17-.25.25-.41.08-.17.04-.31-.02-.43-.06-.11-.56-1.35-.77-1.84-.2-.48-.4-.42-.56-.43-.14 0-.3-.01-.46-.01z"/>
                    </svg>
                    Falar no WhatsApp
                </a>
                
                <button 
                    onclick="fecharModal()"
                    class="flex-1 px-4 sm:px-6 py-3 sm:py-4 bg-neutral-100 text-neutral-700 rounded-xl font-bold text-sm sm:text-base hover:bg-neutral-200 transition-all"
                >
                    Fechar
                </button>
            </div>
        </div>
    </div>
    @endif
    </div>
</div>

<style>
@keyframes fade-in {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.animate-fade-in {
    animation: fade-in 0.3s ease-out;
}

/* Custom Scrollbar para o modal */
.custom-scrollbar::-webkit-scrollbar {
    width: 8px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: linear-gradient(to bottom, #10b981, #059669);
    border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(to bottom, #059669, #047857);
}

/* Firefox */
.custom-scrollbar {
    scrollbar-width: thin;
    scrollbar-color: #10b981 #f1f1f1;
}
</style>

<script>
function fecharModal() {
    document.getElementById('modal-confirmacao').remove();
}

// Fechar com ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && document.getElementById('modal-confirmacao')) {
        fecharModal();
    }
});
</script>
@endif

<script>
// Variáveis globais para armazenar dados da promoção
let promocaoAtual = null;
let horarioSelecionado = null;
const fidelidadeConfig = {!! json_encode([
    'habilitado' => auth()->check(),
    'pontosDisponiveis' => $resumoFidelidade['pontos_atuais'] ?? 0,
    'valorPorPonto' => $resumoFidelidade['valor_por_ponto'] ?? 0,
    'resgateMinimo' => $resumoFidelidade['resgate_minimo'] ?? 0,
    'maxPercentualResgate' => $resumoFidelidade['maximo_percentual_resgate'] ?? 0,
    'pontosPorReal' => $resumoFidelidade['pontos_por_real'] ?? 0,
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!};

window.fidelidadeConfig = fidelidadeConfig;

// Função para buscar horários disponíveis
async function buscarHorariosDisponiveis() {
    const servicoSelect = document.getElementById('servico');
    const dataInput = document.getElementById('data');
    
    if (!servicoSelect.value || !dataInput.value) {
        return;
    }
    
    const servicoId = servicoSelect.value;
    const data = dataInput.value;
    
    // Mostra loading
    document.getElementById('selecione-data-msg').classList.add('hidden');
    document.getElementById('horarios-container').classList.remove('hidden');
    document.getElementById('horarios-loading').classList.remove('hidden');
    document.getElementById('horarios-grid').innerHTML = '';
    document.getElementById('sem-horarios').classList.add('hidden');
    
    try {
        const response = await fetch(`/api/horarios-disponiveis?servico_id=${servicoId}&data=${data}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        const result = await response.json();
        
        // Esconde loading
        document.getElementById('horarios-loading').classList.add('hidden');
        
        if (result.success && result.horarios && result.horarios.length > 0) {
            // Atualiza dia da semana
            document.getElementById('dia-semana').textContent = `(${result.dia_semana})`;
            
            // Renderiza horários
            const grid = document.getElementById('horarios-grid');
            grid.innerHTML = result.horarios.map(horarioObj => {
                const hora = typeof horarioObj === 'string' ? horarioObj : horarioObj.horario;
                return `
                    <button 
                        type="button"
                        onclick="selecionarHorario('${hora}')"
                        class="horario-btn px-4 py-3 border-2 border-neutral-300 rounded-lg text-sm font-semibold text-neutral-700 hover:border-primary-500 hover:bg-primary-50 hover:text-primary-700 transition-all"
                        data-hora="${hora}"
                    >
                        ${hora}
                    </button>
                `;
            }).join('');
        } else {
            // Nenhum horário disponível
            document.getElementById('sem-horarios').classList.remove('hidden');
        }
    } catch (error) {
        console.error('Erro ao buscar horários:', error);
        document.getElementById('horarios-loading').classList.add('hidden');
        document.getElementById('sem-horarios').classList.remove('hidden');
    }
}

// Função para selecionar um horário
function selecionarHorario(hora) {
    // Remove seleção anterior
    document.querySelectorAll('.horario-btn').forEach(btn => {
        btn.classList.remove('border-primary-600', 'bg-primary-500', 'text-white', 'shadow-lg');
        btn.classList.add('border-neutral-300', 'text-neutral-700');
    });
    
    // Adiciona seleção ao horário clicado
    const botao = document.querySelector(`[data-hora="${hora}"]`);
    if (botao) {
        botao.classList.remove('border-neutral-300', 'text-neutral-700');
        botao.classList.add('border-primary-600', 'bg-primary-500', 'text-white', 'shadow-lg');
    }
    
    // Atualiza campo hidden
    document.getElementById('hora').value = hora;
    horarioSelecionado = hora;
}

// Event listeners
document.getElementById('servico').addEventListener('change', function() {
    // Limpa horários quando trocar de serviço
    document.getElementById('horarios-container').classList.add('hidden');
    document.getElementById('selecione-data-msg').classList.remove('hidden');
    document.getElementById('hora').value = '';
    horarioSelecionado = null;
    
    // Busca horários se já tiver data selecionada
    const dataInput = document.getElementById('data');
    if (dataInput.value) {
        buscarHorariosDisponiveis();
    }
    
    // Atualiza resumo de valores
    atualizarResumoValores();
});

document.getElementById('data').addEventListener('change', function() {
    // Limpa horário selecionado
    document.getElementById('hora').value = '';
    horarioSelecionado = null;
    
    // Busca novos horários
    buscarHorariosDisponiveis();
});

const pontosInputElement = document.getElementById('usar_pontos');
const botaoUsarMax = document.getElementById('usar-pontos-max');
const botaoLimparPontos = document.getElementById('limpar-pontos');

if (pontosInputElement) {
    pontosInputElement.addEventListener('input', atualizarResumoValores);
    pontosInputElement.addEventListener('change', atualizarResumoValores);
}

if (botaoUsarMax && pontosInputElement) {
    botaoUsarMax.addEventListener('click', () => {
        const maxPermitido = parseInt(pontosInputElement.dataset.maxPermitido || '0', 10);
        if (maxPermitido > 0) {
            pontosInputElement.value = maxPermitido;
            atualizarResumoValores();
        }
    });
}

if (botaoLimparPontos && pontosInputElement) {
    botaoLimparPontos.addEventListener('click', () => {
        pontosInputElement.value = 0;
        atualizarResumoValores();
    });
}

// Função para atualizar o resumo de valores
function atualizarResumoValores() {
    const servicoSelect = document.getElementById('servico');
    const resumoDiv = document.getElementById('resumo-valores');
    const pontosInput = document.getElementById('usar_pontos');
    const feedback = document.getElementById('usar-pontos-feedback');
    const alerta = document.getElementById('usar-pontos-alerta');
    const botaoMax = document.getElementById('usar-pontos-max');
    const botaoLimpar = document.getElementById('limpar-pontos');
    const config = window.fidelidadeConfig ?? { habilitado: false };

    if (!servicoSelect || !servicoSelect.value) {
        resumoDiv.innerHTML = `
            <div class="text-center py-8">
                <svg class="w-12 h-12 mx-auto text-emerald-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-sm text-emerald-600">Selecione um serviço para ver os valores</p>
            </div>
        `;

        if (pontosInput) {
            pontosInput.disabled = true;
            pontosInput.value = 0;
            if (feedback) {
                feedback.textContent = 'Selecione um serviço para saber quantos pontos pode resgatar.';
            }
            if (alerta) {
                alerta.classList.add('hidden');
                alerta.textContent = '';
            }
            if (botaoMax) {
                botaoMax.classList.add('hidden');
            }
        }

        return;
    }

    const selectedOption = servicoSelect.options[servicoSelect.selectedIndex];
    const preco = parseFloat(selectedOption.dataset.preco) || 0;

    let valorDescontoPromocao = 0;
    if (promocaoAtual && promocaoAtual.valor_desconto) {
        valorDescontoPromocao = parseFloat(promocaoAtual.valor_desconto) || 0;
    }

    const valorBase = Math.max(0, preco - valorDescontoPromocao);

    let valorDescontoPontos = 0;
    let pontosAplicados = 0;
    let maxPontosPermitidos = 0;

    if (pontosInput && config.habilitado) {
        const saldoDisponivel = Number(config.pontosDisponiveis) || 0;
        const resgateMinimo = Number(config.resgateMinimo) || 0;
        const valorPorPonto = Number(config.valorPorPonto) || 0;
        const maxPercentual = Number(config.maxPercentualResgate) || 0;

        const maxPorValor = valorPorPonto > 0 && maxPercentual > 0
            ? Math.floor((valorBase * (maxPercentual / 100)) / valorPorPonto)
            : 0;

        maxPontosPermitidos = Math.max(0, Math.min(saldoDisponivel, maxPorValor));

        if (valorBase <= 0 || valorPorPonto <= 0 || maxPontosPermitidos <= 0) {
            pontosInput.disabled = saldoDisponivel <= 0;
            pontosInput.dataset.maxPermitido = 0;
            pontosInput.max = 0;
            pontosInput.value = 0;

            if (feedback) {
                if (saldoDisponivel > 0 && resgateMinimo > 0) {
                    feedback.textContent = `Este serviço permite resgates a partir de ${resgateMinimo} pontos. Continue acumulando!`;
                } else if (saldoDisponivel > 0) {
                    feedback.textContent = 'Selecione um serviço diferente para utilizar seus pontos.';
                } else {
                    feedback.textContent = 'Você ainda não possui pontos suficientes para resgatar.';
                }
            }

            if (botaoMax) {
                botaoMax.classList.add('hidden');
            }
        } else {
            pontosInput.disabled = false;
            pontosInput.dataset.maxPermitido = maxPontosPermitidos;
            pontosInput.max = maxPontosPermitidos;

            if (feedback) {
                const valorMaximo = (maxPontosPermitidos * valorPorPonto).toFixed(2).replace('.', ',');
                feedback.textContent = `Você pode usar até ${maxPontosPermitidos} pontos (R$ ${valorMaximo}) neste agendamento.`;
            }

            if (botaoMax) {
                botaoMax.classList.toggle('hidden', maxPontosPermitidos <= 0);
            }
        }

        if (botaoLimpar) {
            botaoLimpar.classList.toggle('hidden', saldoDisponivel <= 0);
        }

        const pontosDesejados = Math.min(Number(pontosInput.value || 0), maxPontosPermitidos);

        if (alerta) {
            alerta.classList.add('hidden');
            alerta.textContent = '';
        }

        if (pontosDesejados > 0) {
            if (pontosDesejados < resgateMinimo) {
                if (alerta) {
                    alerta.textContent = `O resgate mínimo é de ${resgateMinimo} pontos.`;
                    alerta.classList.remove('hidden');
                }
            } else {
                pontosAplicados = pontosDesejados;
                valorDescontoPontos = Math.min(valorBase, pontosAplicados * valorPorPonto);
            }
        }
    }

    const temDesconto = valorDescontoPromocao > 0 || valorDescontoPontos > 0;
    const valorFinal = Math.max(0, valorBase - valorDescontoPontos);
    const valorSinal = Math.max(0, valorFinal * 0.30);
    const valorRestante = Math.max(0, valorFinal - valorSinal);
    const pontosPrevistos = config.habilitado
        ? Math.max(0, Math.floor(valorFinal * (Number(config.pontosPorReal) || 0)))
        : 0;
    const valorPrevistoPontos = pontosPrevistos * (Number(config.valorPorPonto) || 0);

    let detalhesHtml = `
        <div class="flex justify-between items-center text-sm">
            <span class="text-neutral-600">${temDesconto ? 'Preço Original:' : 'Preço do Serviço:'}</span>
            <span class="font-semibold ${temDesconto ? 'text-neutral-500 line-through' : 'text-neutral-900'}">R$ ${preco.toFixed(2).replace('.', ',')}</span>
        </div>
    `;

    if (valorDescontoPromocao > 0) {
        detalhesHtml += `
            <div class="flex justify-between items-center text-sm">
                <span class="text-green-600 font-semibold">Desconto promocional:</span>
                <span class="font-bold text-green-600">- R$ ${valorDescontoPromocao.toFixed(2).replace('.', ',')}</span>
            </div>
        `;
    }

    if (valorDescontoPontos > 0) {
        detalhesHtml += `
            <div class="flex justify-between items-center text-sm">
                <span class="text-accent-600 font-semibold">Desconto com pontos:</span>
                <span class="font-bold text-accent-600">- R$ ${valorDescontoPontos.toFixed(2).replace('.', ',')}</span>
            </div>
        `;
    }

    const classeValorTotal = temDesconto ? 'text-green-600' : 'text-emerald-700';

    resumoDiv.innerHTML = `
        <div class="space-y-4">
            ${detalhesHtml}

            <div class="flex justify-between items-center pt-3 border-t-2 border-emerald-300">
                <span class="font-bold text-neutral-900">Valor Total:</span>
                <span class="text-2xl font-bold ${classeValorTotal}">R$ ${valorFinal.toFixed(2).replace('.', ',')}</span>
            </div>

            <div class="bg-white rounded-lg p-4 space-y-2 border border-emerald-200">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-neutral-600">Sinal (30%):</span>
                    <span class="text-lg font-bold text-primary-600">R$ ${valorSinal.toFixed(2).replace('.', ',')}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-xs text-neutral-500">Restante (70% no dia):</span>
                    <span class="text-sm text-neutral-700">R$ ${valorRestante.toFixed(2).replace('.', ',')}</span>
                </div>
            </div>

            ${config.habilitado && pontosPrevistos > 0 ? `
                <div class="bg-white border border-dashed border-accent-200 rounded-lg p-3 text-xs text-neutral-600">
                    Ao concluir este atendimento você soma <span class="font-semibold text-neutral-800">${pontosPrevistos}</span> ponto(s)
                    (≈ R$ ${valorPrevistoPontos.toFixed(2).replace('.', ',')}).
                </div>
            ` : ''}
        </div>
    `;
}

// Atualiza resumo quando seleciona serviço
// Atualiza resumo ao carregar a página (se já houver serviço selecionado)
document.addEventListener('DOMContentLoaded', function() {
    atualizarResumoValores();
});

function mostrarResumo(valorOriginal, valorDesconto, valorFinal) {
    const resumo = document.getElementById('resumo-valores');
    const linhaDesconto = document.getElementById('linha-desconto');
    
    document.getElementById('valor-original').textContent = formatarMoeda(valorOriginal);
    document.getElementById('valor-total').textContent = formatarMoeda(valorFinal);
    
    // Calcula e exibe o valor do sinal (30%)
    const valorSinal = valorFinal * 0.30;
    const valorRestante = valorFinal - valorSinal;
    
    // Adiciona informação do sinal se ainda não existe
    let linhaSinal = document.getElementById('linha-sinal');
    if (!linhaSinal) {
        linhaSinal = document.createElement('div');
        linhaSinal.id = 'linha-sinal';
        linhaSinal.className = 'pt-3 border-t border-primary-200 space-y-2';
        linhaSinal.innerHTML = `
            <div class="flex justify-between items-center text-sm">
                <span class="text-neutral-600">Sinal (30%):</span>
                <span id="valor-sinal" class="font-bold text-primary-600"></span>
            </div>
            <div class="flex justify-between items-center text-xs">
                <span class="text-neutral-500">Restante (no dia):</span>
                <span id="valor-restante" class="text-neutral-700"></span>
            </div>
        `;
        document.querySelector('#resumo-valores .space-y-3').appendChild(linhaSinal);
    }
    
    document.getElementById('valor-sinal').textContent = formatarMoeda(valorSinal);
    document.getElementById('valor-restante').textContent = formatarMoeda(valorRestante);
    
    if (valorDesconto > 0) {
        document.getElementById('valor-desconto').textContent = '- ' + formatarMoeda(valorDesconto);
        linhaDesconto.classList.remove('hidden');
        document.getElementById('valor-original').classList.add('line-through', 'text-neutral-500');
        document.getElementById('valor-total').classList.add('text-green-600');
        document.getElementById('valor-total').classList.remove('text-brand-gradient');
    } else {
        linhaDesconto.classList.add('hidden');
        document.getElementById('valor-original').classList.remove('line-through', 'text-neutral-500');
        document.getElementById('valor-total').classList.remove('text-green-600');
        document.getElementById('valor-total').classList.add('text-brand-gradient');
    }
    
    resumo.classList.remove('hidden');
}

async function validarCupom() {
    const codigoInput = document.getElementById('codigo_cupom');
    const codigo = codigoInput.value.trim().toUpperCase();
    const servicoId = document.getElementById('servico').value;
    const btnAplicar = document.getElementById('btn-aplicar-cupom');
    const resultado = document.getElementById('cupom-resultado');
    
    // Validações
    if (!servicoId) {
        mostrarResultadoCupom('erro', 'Selecione um serviço primeiro');
        return;
    }
    
    if (!codigo) {
        mostrarResultadoCupom('erro', 'Digite um código de cupom');
        return;
    }
    
    // Mostra loading
    btnAplicar.disabled = true;
    btnAplicar.innerHTML = '<svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
    
    try {
        const response = await fetch('/api/validar-cupom', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ 
                codigo: codigo,
                servico_id: servicoId 
            })
        });
        
        const data = await response.json();
        
        if (data.valido) {
            // Salva dados da promoção
            promocaoAtual = data;
            
            // Atualiza campos hidden
            document.getElementById('promocao_id').value = data.promocao_id;
            document.getElementById('codigo_cupom_validado').value = data.codigo;
            
            // Atualiza resumo de valores
            atualizarResumoValores();
            
            // Mostra mensagem de sucesso
            mostrarResultadoCupom('sucesso', data.mensagem, data.titulo);
            
            // Desabilita campo e muda botão para "Remover"
            codigoInput.disabled = true;
            btnAplicar.textContent = 'Remover';
            btnAplicar.onclick = removerCupom;
            btnAplicar.classList.remove('from-accent-500', 'to-accent-400', 'hover:from-accent-600', 'hover:to-accent-500');
            btnAplicar.classList.add('bg-neutral-400', 'hover:bg-neutral-500');
            
        } else {
            mostrarResultadoCupom('erro', data.mensagem);
        }
        
    } catch (error) {
        console.error('Erro ao validar cupom:', error);
        mostrarResultadoCupom('erro', 'Erro ao validar cupom. Tente novamente.');
    } finally {
        btnAplicar.disabled = false;
        btnAplicar.innerHTML = promocaoAtual ? 'Remover' : 'Aplicar';
    }
}

function removerCupom() {
    const codigoInput = document.getElementById('codigo_cupom');
    const btnAplicar = document.getElementById('btn-aplicar-cupom');
    const servicoSelect = document.getElementById('servico');
    
    // Limpa dados
    promocaoAtual = null;
    codigoInput.value = '';
    codigoInput.disabled = false;
    document.getElementById('promocao_id').value = '';
    document.getElementById('codigo_cupom_validado').value = '';
    document.getElementById('cupom-resultado').innerHTML = '';
    document.getElementById('cupom-resultado').classList.add('hidden');
    
    // Restaura botão
    btnAplicar.textContent = 'Aplicar';
    btnAplicar.onclick = validarCupom;
    btnAplicar.classList.add('from-accent-500', 'to-accent-400', 'hover:from-accent-600', 'hover:to-accent-500');
    btnAplicar.classList.remove('bg-neutral-400', 'hover:bg-neutral-500');
    
    // Atualiza resumo sem desconto
    atualizarResumoValores();
    
    // Atualiza resumo com valor original
    if (servicoSelect.value) {
        const selectedOption = servicoSelect.options[servicoSelect.selectedIndex];
        const texto = selectedOption.text;
        const precoMatch = texto.match(/R\$ ([\d,\.]+)/);
        
        if (precoMatch) {
            const preco = parseFloat(precoMatch[1].replace('.', '').replace(',', '.'));
            mostrarResumo(preco, 0, preco);
        }
    }
}

function mostrarResultadoCupom(tipo, mensagem, titulo = null) {
    const resultado = document.getElementById('cupom-resultado');
    
    if (tipo === 'sucesso') {
        resultado.innerHTML = `
            <div class="flex items-start gap-3 p-4 bg-green-50 border-2 border-green-200 rounded-xl animate-fade-in">
                <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div class="flex-1">
                    ${titulo ? `<p class="font-bold text-green-900 mb-1">${titulo}</p>` : ''}
                    <p class="text-sm text-green-700 font-medium">${mensagem}</p>
                </div>
            </div>
        `;
    } else {
        resultado.innerHTML = `
            <div class="flex items-start gap-3 p-4 bg-red-50 border-2 border-red-200 rounded-xl">
                <div class="w-6 h-6 bg-red-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm text-red-700 font-medium">${mensagem}</p>
                </div>
            </div>
        `;
    }
    
    resultado.classList.remove('hidden');
}

function formatarMoeda(valor) {
    return 'R$ ' + valor.toFixed(2).replace('.', ',');
}

// Permite aplicar cupom com Enter
document.getElementById('codigo_cupom').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        validarCupom();
    }
});

// Gerenciar seleção visual das formas de pagamento
document.querySelectorAll('input[name="forma_pagamento"]').forEach(radio => {
    radio.addEventListener('change', function() {
        // Remove seleção de todos os cards
        document.querySelectorAll('.payment-card').forEach(card => {
            card.classList.remove('border-primary-500', 'border-primary-600', 'bg-primary-50', 'shadow-lg', 'shadow-xl');
            card.classList.add('border-neutral-300', 'bg-white');
            
            const checkmark = card.querySelector('.payment-checkmark');
            if (checkmark) {
                checkmark.classList.add('hidden');
                checkmark.classList.remove('flex');
            }
        });
        
        // Adiciona seleção ao card clicado
        const selectedCard = this.nextElementSibling;
        const selectedCheckmark = selectedCard.querySelector('.payment-checkmark');
        
        selectedCard.classList.remove('border-neutral-300', 'bg-white');
        selectedCard.classList.add('border-primary-500', 'bg-primary-50', 'shadow-lg');
        
        if (selectedCheckmark) {
            selectedCheckmark.classList.remove('hidden');
            selectedCheckmark.classList.add('flex');
        }
    });
});
</script>

@endsection
