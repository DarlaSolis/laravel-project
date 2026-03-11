<x-admin-layout title="Roles | Farmacon" :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard')
    ],
    [
        'name' => 'Roles',
        'href' => route('admin.roles.index')
    ],
    [
        'name' => 'Editar',
    ],
]">
    <div class="mt-8 flow-root bg-white shadow overflow-hidden sm:rounded-lg border border-gray-200"><div class="p-6">
        <form action="{{ route('admin.roles.update', $role) }}" method="POST">
            @csrf
            @method('PUT')

            <x-input label="Nombre" name="name" placeholder="Nombre del rol" value="{{ old('name', $role->name) }}">

            </x-input>
            <div class="flex justify-end mt-4">
                <x-button type="submit" blue>Actualizar</x-button>
            </div>
        </form>
    </div></div>
</x-admin-layout>
