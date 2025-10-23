@props ([
    'title' => config('app.name', 'laravel'),
    'breadcrumbs' => []])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- CARGAR ALPINE.JS SIN DEFER - MÁS ARRIBA -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://kit.fontawesome.com/c3672ea99d.js" crossorigin="anonymous"></script>

    <!-- WireUI -->
    <wireui:scripts />

    <!-- Styles -->
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-50">
@include('layouts.includes.admin.navigation')
@include('layouts.includes.admin.sidebar')

<div class="p-4 sm:ml-64">
    <div class="mt-14 flex items-center justify-between w-full">
        @include('layouts.includes.admin.breadcrumb', ['breadcrumbs' => $breadcrumbs])

    </div>
    {{ $slot }}
</div>

@stack('modals')

@livewireScripts
<script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</nav>

<!-- Script para inicializar Alpine.js manualmente -->
<script>
    // Esperar a que el DOM esté listo
    document.addEventListener('DOMContentLoaded', function() {
        // Verificar si Alpine está disponible
        if (typeof Alpine !== 'undefined') {
            console.log('Alpine.js está disponible, inicializando...');
            // Forzar la inicialización
            Alpine.start();
        } else {
            console.error('Alpine.js NO está disponible');
            // Cargar Alpine.js manualmente
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js';
            script.onload = function() {
                console.log('Alpine.js cargado manualmente, inicializando...');
                Alpine.start();
            };
            document.head.appendChild(script);
        }
    });
</script>
</body>
</html>
