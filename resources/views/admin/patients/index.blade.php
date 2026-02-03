<x-admin-layout title="Pacientes | Farmacon" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Pacientes']
]">
    @livewire('admin.datatables.patient-table')

</x-admin-layout>
