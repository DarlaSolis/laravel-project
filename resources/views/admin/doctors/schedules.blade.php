<x-admin-layout title="Horarios del Doctor | Farmacon">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Horarios para {{ $doctor->user->name }}</h1>
        <p class="text-gray-600 mt-2">Módulo de horarios en construcción.</p>
    </div>

    <div class="mt-8 bg-white shadow sm:rounded-lg mb-6 p-6">
        <div class="flex items-center text-yellow-600 mb-4">
            <i class="fa-solid fa-triangle-exclamation text-2xl mr-3"></i>
            <h2 class="text-xl font-semibold text-gray-800">Próximamente</h2>
        </div>
        <p class="text-gray-700">El módulo para gestión de horarios automatizados será implementado en la siguiente iteración.</p>
        <div class="mt-6">
            <x-button secondary href="{{ route('admin.doctors.index') }}">
                <i class="fa-solid fa-arrow-left mr-2"></i> Volver a Doctores
            </x-button>
        </div>
    </div>
</x-admin-layout>
