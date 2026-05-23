<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Gestor de Tareas') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-['Inter'] antialiased">

    {{-- Navbar --}}
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">

                {{-- Logo --}}
                <div class="flex items-center gap-8">
                    <a href="{{ route('tasks.index') }}" class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                        </div>
                        <span class="font-semibold text-gray-900 text-lg">AgendaMe</span>
                    </a>

                    {{-- Links de navegación --}}
                    @auth
                    <div class="hidden sm:flex items-center gap-1">
                        <a href="{{ route('tasks.index') }}"
                            class="px-3 py-2 rounded-lg text-sm font-medium transition-colors
                            {{ request()->routeIs('tasks.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-100' }}">
                            Mis Tareas
                        </a>
                        <a href="{{ route('tasks.create') }}"
                            class="px-3 py-2 rounded-lg text-sm font-medium transition-colors
                            {{ request()->routeIs('tasks.create') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-100' }}">
                            + Nueva Tarea
                        </a>
                    </div>
                    @endauth
                </div>

                {{-- Usuario --}}
                <div class="flex items-center gap-3">
                    @auth
                        <div class="hidden sm:flex items-center gap-2 text-sm text-gray-600">
                            <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center font-medium text-indigo-700">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <span>{{ auth()->user()->name }}</span>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="text-sm text-gray-500 hover:text-gray-700 px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors">
                                Cerrar sesión
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                            class="text-sm text-gray-600 hover:text-gray-900 px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors">
                            Iniciar sesión
                        </a>
                        <a href="{{ route('register') }}"
                            class="text-sm text-white bg-indigo-600 hover:bg-indigo-700 px-4 py-2 rounded-lg transition-colors">
                            Registrarse
                        </a>
                    @endauth
                </div>

            </div>
        </div>
    </nav>

    {{-- Header de página --}}
    @isset($header)
        <header class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                {{ $header }}
            </div>
        </header>
    @endisset

    {{-- Contenido --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{ $slot }}
    </main>

</body>
</html>
