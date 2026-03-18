<x-admin-layout title="Doctores | Farmacon">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Doctores</h1>
        <x-wire-button primary href="{{ route('admin.doctors.create') }}">
            <i class="fa-solid fa-plus mr-2"></i>
            Nuevo Doctor
        </x-wire-button>
    </div>

    <x-wire-card>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th class="px-6 py-3">Nombre</th>
                    <th class="px-6 py-3">Email</th>
                    <th class="px-6 py-3">Especialidad</th>
                    <th class="px-6 py-3">Cédula Profesional</th>
                    <th class="px-6 py-3">Biografía</th>
                    <th class="px-6 py-3">Acciones</th>
                </tr>
                </thead>
                <tbody>
                @foreach($doctors as $doctor)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-900">
                            {{ $doctor->user->name }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $doctor->user->email }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $doctor->specialty->name }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $doctor->license_number ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 max-w-xs truncate">
                            {{ $doctor->biography ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-2">
                                {{-- Botón Editar --}}
                                <x-wire-button
                                    href="{{ route('admin.doctors.edit', $doctor) }}"
                                    blue
                                    xs
                                >
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </x-wire-button>

                                {{-- Botón Eliminar con validación para no eliminar al admin --}}
                                {{-- Botón Eliminar con validación para no eliminar al admin --}}
                                @if($doctor->user->id !== 1)
                                    <form action="{{ route('admin.doctors.destroy', $doctor) }}" method="POST" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <x-wire-button type="submit" red xs>
                                            <i class="fa-solid fa-trash"></i>
                                        </x-wire-button>
                                    </form>
                                @else
                                    <x-wire-button type="button" red xs disabled>
                                        <i class="fa-solid fa-trash"></i>
                                    </x-wire-button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            @if($doctors->isEmpty())
                <div class="text-center py-8 text-gray-500">
                    No hay doctores registrados
                </div>
            @endif
        </div>
    </x-wire-card>
</x-admin-layout>


