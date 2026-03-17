<x-admin-layout title="Nuevo Ticket | Farmacon" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Soporte', 'href' => route('admin.tickets.index')],
    ['name' => 'Nuevo Ticket']
]">

    <div class="px-4 py-4 sm:px-6 lg:px-8">
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6 max-w-4xl">
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Reportar un problema</h3>
                <p class="mt-1 text-sm text-gray-500">Describe tu problema o duda y nuestro equipo de soporte se pondrá en contacto contigo.</p>
            </div>

            <form action="{{ route('admin.tickets.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Título del problema</label>
                    <input type="text" name="title" id="title" class="bg-gray-50 border border-blue-400 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 outline-none shadow-sm" required value="{{ old('title') }}">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descripción detallada</label>
                    <textarea name="description" id="description" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 outline-none shadow-sm" required>{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-x-3 border-t border-gray-200 pt-5">
                    <a href="{{ route('admin.tickets.index') }}" class="text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-lg px-4 py-2 focus:ring-4 focus:outline-none focus:ring-gray-200 shadow-sm">
                        Cancelar
                    </a>
                    <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2 shadow-sm focus:outline-none">
                        Enviar Ticket
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
