<x-admin-layout title="Roles | Farmacon" :breadcrumbs="[
    ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['name' => 'Roles']
]">
    @livewire('admin.datatables.role-table')
</x-admin-layout>
