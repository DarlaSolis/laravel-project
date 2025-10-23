{{-- resources/views/components/admin-layout.blade.php --}}
@props(['title' => 'Panel Admin', 'breadcrumbs' => []])

    <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>

    {{-- Estilos principales --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Estilos Livewire --}}
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-50">
@include('layouts.includes.admin.navigation')
@include('layouts.includes.admin.sidebar')

<div class="p-4 sm:ml-64">
    {{-- Breadcrumb --}}
    @include('layouts.includes.admin.breadcrumb', ['breadcrumbs' => $breadcrumbs])

    {{-- Aquí se renderiza Livewire y el contenido --}}
    {{ $slot }}
</div>

@stack('modals')

@livewireScripts
</body>

</html>
