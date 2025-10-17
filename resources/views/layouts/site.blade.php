<!DOCTYPE html>
<html lang="pt-BR" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name') }}</title>

    @if (app()->environment('testing'))
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.4/tailwind.min.css">
    @else
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="min-h-full bg-white text-neutral-900 antialiased font-sans">
    <div class="relative min-h-screen flex flex-col">
        <!-- Header -->
        <header class="sticky top-0 z-50 border-b border-primary-100 bg-white/95 backdrop-blur-sm shadow-sm">
            <nav class="mx-auto flex w-full max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="group flex items-center gap-3 text-lg font-semibold tracking-tight transition-transform hover:scale-105">
                    <img src="{{ asset('images/logo_chris.png') }}" alt="Chris Pincel Mágico" class="h-12 w-auto">
                    <span class="text-brand-gradient hidden sm:inline">Chris Pincel Mágico</span>
                </a>

                @php($links = [
                    ['route' => 'home', 'label' => 'Início'],
                    ['route' => 'servicos.index', 'label' => 'Serviços'],
                    ['route' => 'promocoes.index', 'label' => 'Promoções'],
                    ['route' => 'agendamentos.create', 'label' => 'Agendar'],
                ])

                <!-- Desktop Navigation -->
                <ul class="hidden md:flex items-center gap-1">
                    @foreach($links as $link)
                        <li>
                            <a 
                                href="{{ route($link['route']) }}" 
                                @class([
                                    'px-4 py-2 rounded-lg text-sm font-medium transition-all',
                                    'bg-gradient-to-r from-primary-500 to-primary-400 text-white shadow-md' => request()->routeIs($link['route']),
                                    'text-neutral-700 hover:bg-primary-100 hover:text-primary-600' => !request()->routeIs($link['route'])
                                ])
                            >
                                {{ $link['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>

                <!-- User Menu / Auth Buttons -->
                <div class="hidden md:flex items-center gap-2">
                    @auth
                        <a href="{{ route('conta.index') }}" class="px-4 py-2 text-sm font-medium text-neutral-700 hover:text-primary-600 transition-colors">
                            Minha Conta
                        </a>
                        @if(auth()->user()->is_admin)
                            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 text-sm font-medium text-neutral-700 hover:text-primary-600 transition-colors">
                                Admin
                            </a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-neutral-700 hover:text-red-600 transition-colors">
                                Sair
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-neutral-700 hover:text-primary-600 transition-colors">
                            Entrar
                        </a>
                        <a href="{{ route('register') }}" class="px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-primary-500 to-primary-400 rounded-lg shadow-md hover:shadow-lg hover:scale-105 transition-all">
                            Cadastre-se
                        </a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <button 
                    type="button" 
                    class="md:hidden inline-flex items-center justify-center p-2 rounded-lg text-neutral-700 hover:bg-primary-100 transition-colors"
                    onclick="document.getElementById('mobile-menu').classList.toggle('hidden')"
                >
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </nav>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="hidden md:hidden border-t border-primary-100 bg-white">
                <ul class="px-4 py-3 space-y-1">
                    @foreach($links as $link)
                        <li>
                            <a 
                                href="{{ route($link['route']) }}" 
                                @class([
                                    'block px-4 py-3 rounded-lg text-sm font-medium transition-colors',
                                    'bg-gradient-to-r from-primary-500 to-primary-400 text-white' => request()->routeIs($link['route']),
                                    'text-neutral-700 hover:bg-primary-100' => !request()->routeIs($link['route'])
                                ])
                            >
                                {{ $link['label'] }}
                            </a>
                        </li>
                    @endforeach
                    
                    <li class="pt-3 border-t border-primary-100 mt-3">
                        @auth
                            <a href="{{ route('conta.index') }}" class="block px-4 py-3 rounded-lg text-sm font-medium text-neutral-700 hover:bg-primary-100">
                                Minha Conta
                            </a>
                            @if(auth()->user()->is_admin)
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 rounded-lg text-sm font-medium text-neutral-700 hover:bg-primary-100 mt-1">
                                    Admin
                                </a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}" class="mt-1">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-3 rounded-lg text-sm font-medium text-red-600 hover:bg-red-50">
                                    Sair
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="block px-4 py-3 rounded-lg text-sm font-medium text-neutral-700 hover:bg-primary-100">
                                Entrar
                            </a>
                            <a href="{{ route('register') }}" class="block px-4 py-3 rounded-lg text-sm font-semibold text-white bg-gradient-to-r from-primary-500 to-primary-400 mt-1 text-center">
                                Cadastre-se
                            </a>
                        @endauth
                    </li>
                </ul>
            </div>
        </header>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mt-4">
                <div class="p-4 rounded-lg bg-green-50 border border-green-200 text-green-800">
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mt-4">
                <div class="p-4 rounded-lg bg-red-50 border border-red-200 text-red-800">
                    <p class="text-sm font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Main Content -->
        <main class="flex-1">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="mt-auto border-t border-primary-100 bg-gradient-to-b from-white to-primary-50">
            <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- About -->
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <img src="{{ asset('images/logo_chris.png') }}" alt="Chris Pincel Mágico" class="h-10 w-auto">
                            <span class="text-brand-gradient font-bold text-lg">Chris Pincel Mágico</span>
                        </div>
                        <p class="text-sm text-neutral-600 leading-relaxed">
                            Beleza autoral com tecnologia. Transformando sonhos em realidade através da arte da maquiagem.
                        </p>
                    </div>

                    <!-- Quick Links -->
                    <div>
                        <h3 class="font-semibold text-neutral-900 mb-4">Links Rápidos</h3>
                        <ul class="space-y-2">
                            @foreach($links as $link)
                                <li>
                                    <a href="{{ route($link['route']) }}" class="text-sm text-neutral-600 hover:text-primary-600 transition-colors">
                                        {{ $link['label'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Contact -->
                    <div>
                        <h3 class="font-semibold text-neutral-900 mb-4">Contato</h3>
                        <ul class="space-y-3">
                            <li class="flex items-start gap-2 text-sm text-neutral-600">
                                <svg class="w-5 h-5 text-primary-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <span>{{ site_info('telefone') }}</span>
                            </li>
                            <li class="flex items-start gap-2 text-sm text-neutral-600">
                                <svg class="w-5 h-5 text-primary-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <span>{{ site_info('email') }}</span>
                            </li>
                            @foreach(enderecos_estudios() as $estudio)
                            <li class="flex items-start gap-2 text-sm text-neutral-600">
                                <svg class="w-5 h-5 text-primary-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <div>
                                    <div class="font-semibold text-neutral-700">{{ $estudio['nome'] }}</div>
                                    <div>{{ $estudio['endereco'] }}</div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="mt-8 pt-8 border-t border-primary-200 text-center">
                    <p class="text-sm text-neutral-500">
                        &copy; {{ date('Y') }} Chris Pincel Mágico. Todos os direitos reservados.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
