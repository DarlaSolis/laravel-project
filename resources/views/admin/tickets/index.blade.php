<x-admin-layout title="Soporte | Farmacon" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Soporte']
]">

    <div class="px-4 py-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto"></div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <a href="{{ route('admin.tickets.create') }}" class="block rounded-md bg-blue-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                    Nuevo Ticket
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 mb-4 mt-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="mt-8 flow-root bg-white shadow overflow-hidden sm:rounded-lg border border-gray-200">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead>
                        <tr>
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-xs font-semibold uppercase text-gray-500 sm:pl-6">ID</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold uppercase text-gray-500">USUARIO</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold uppercase text-gray-500">TÍTULO</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold uppercase text-gray-500">ESTADO</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold uppercase text-gray-500">FECHA</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($tickets as $ticket)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">#{{ $ticket->id }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-900 font-semibold">{{ $ticket->user->name ?? 'Usuario Desconocido' }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $ticket->title }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    @if($ticket->status === 'Abierto')
                                        <span class="inline-flex items-center rounded-md bg-yellow-50 px-2 py-1 text-xs font-bold text-yellow-800 ring-1 ring-inset ring-yellow-600/20">Abierto</span>
                                    @elseif($ticket->status === 'Cerrado')
                                        <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-bold text-green-700 ring-1 ring-inset ring-green-600/20">Cerrado</span>
                                    @else
                                        <span class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-bold text-gray-600 ring-1 ring-inset ring-gray-600/20">{{ $ticket->status }}</span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-4 text-center text-sm text-gray-500">
                                    No hay tickets de soporte registrados.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
