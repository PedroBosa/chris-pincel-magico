@extends('layouts.site')

@section('title', 'Criar conta | Chris Pincel Mágico')

@section('content')
<div class="min-h-[calc(100vh-16rem)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-white via-primary-50 to-accent-50">
    <div class="max-w-md w-full space-y-8">
        <!-- Header -->
        <div class="text-center">
            <div class="flex justify-center mb-4">
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-primary-500 to-primary-400 flex items-center justify-center text-white font-bold text-xl shadow-lg">
                    CP
                </div>
            </div>
            <h2 class="text-3xl font-bold text-neutral-900 mb-2">
                Crie sua conta
            </h2>
            <p class="text-neutral-600">
                Junte-se a mais de 2.500 clientes satisfeitas
            </p>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-2xl shadow-xl p-8 border border-primary-100">
            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-neutral-700 mb-2">
                        Nome completo
                    </label>
                    <input 
                        id="name" 
                        name="name" 
                        type="text" 
                        value="{{ old('name') }}"
                        required 
                        autofocus
                        class="w-full px-4 py-3 border border-primary-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all @error('name') border-red-500 @enderror"
                        placeholder="Seu nome"
                    >
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-neutral-700 mb-2">
                        Email
                    </label>
                    <input 
                        id="email" 
                        name="email" 
                        type="email" 
                        value="{{ old('email') }}"
                        required
                        class="w-full px-4 py-3 border border-primary-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all @error('email') border-red-500 @enderror"
                        placeholder="seu@email.com"
                    >
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label for="telefone" class="block text-sm font-medium text-neutral-700 mb-2">
                        Telefone/WhatsApp
                    </label>
                    <input 
                        id="telefone" 
                        name="telefone" 
                        type="tel" 
                        value="{{ old('telefone') }}"
                        class="w-full px-4 py-3 border border-primary-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all @error('telefone') border-red-500 @enderror"
                        placeholder="(85) 98765-4321"
                    >
                    @error('telefone')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-neutral-500">Para confirmações e lembretes de agendamento</p>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-neutral-700 mb-2">
                        Senha
                    </label>
                    <input 
                        id="password" 
                        name="password" 
                        type="password" 
                        required
                        class="w-full px-4 py-3 border border-primary-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all @error('password') border-red-500 @enderror"
                        placeholder="Mínimo 8 caracteres"
                    >
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Confirmation -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-neutral-700 mb-2">
                        Confirmar senha
                    </label>
                    <input 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        type="password" 
                        required
                        class="w-full px-4 py-3 border border-primary-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
                        placeholder="Digite a senha novamente"
                    >
                </div>

                <!-- Terms -->
                <div class="flex items-start">
                    <input 
                        id="terms" 
                        name="terms" 
                        type="checkbox" 
                        required
                        class="mt-1 h-4 w-4 text-primary-600 focus:ring-primary-500 border-primary-300 rounded"
                    >
                    <label for="terms" class="ml-2 text-sm text-neutral-600">
                        Concordo com os <a href="#" class="text-primary-600 hover:text-primary-700 font-medium">termos de uso</a> e 
                        <a href="#" class="text-primary-600 hover:text-primary-700 font-medium">política de privacidade</a>
                    </label>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit"
                    class="w-full py-3 px-4 bg-gradient-to-r from-primary-500 to-primary-400 text-white font-semibold rounded-lg shadow-md hover:shadow-lg hover:scale-[1.02] transition-all duration-200"
                >
                    Criar minha conta
                </button>
            </form>

            <!-- Login Link -->
            <div class="mt-6 text-center">
                <p class="text-sm text-neutral-600">
                    Já tem uma conta?
                    <a href="{{ route('login') }}" class="font-semibold text-primary-600 hover:text-primary-700">
                        Entrar agora
                    </a>
                </p>
            </div>
        </div>

        <!-- Benefits -->
        <div class="bg-white/80 backdrop-blur-sm rounded-xl p-6 border border-primary-100">
            <h3 class="text-sm font-semibold text-neutral-900 mb-3">Ao criar sua conta você terá:</h3>
            <ul class="space-y-2">
                <li class="flex items-start gap-2 text-sm text-neutral-600">
                    <svg class="w-5 h-5 text-primary-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Agendamento online rápido e fácil</span>
                </li>
                <li class="flex items-start gap-2 text-sm text-neutral-600">
                    <svg class="w-5 h-5 text-primary-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Acúmulo de pontos no programa de fidelidade</span>
                </li>
                <li class="flex items-start gap-2 text-sm text-neutral-600">
                    <svg class="w-5 h-5 text-primary-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Acesso a promoções exclusivas</span>
                </li>
                <li class="flex items-start gap-2 text-sm text-neutral-600">
                    <svg class="w-5 h-5 text-primary-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Histórico completo de atendimentos</span>
                </li>
            </ul>
        </div>

        <!-- Back to Home -->
        <div class="text-center">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm text-neutral-600 hover:text-primary-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Voltar para o início</span>
            </a>
        </div>
    </div>
</div>
@endsection
