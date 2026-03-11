{{-- resources/views/admin/doctors/edit.blade.php --}}
<x-admin-layout title="Editar Doctor | Farmacon" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Doctores', 'href' => route('admin.doctors.index')],
    ['name' => 'Editar'],
]">

    <form action="{{ route('admin.doctors.update', $doctor) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Encabezado con información del doctor y N/A --}}
        <x-card class="mb-8">
            <div class="lg:flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <img src="{{ $doctor->user->profile_photo_url }}"
                         alt="{{ $doctor->user->name }}"
                         class="w-20 h-20 rounded-full object-cover object-center">
                    <div>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ $doctor->user->name }}
                        </p>
                        <div class="flex flex-col lg:flex-row lg:space-x-4 mt-2 text-sm text-gray-600">
                            <p>
                                <span class="font-semibold">Cédula:</span>
                                {{ $doctor->license_number ?? 'N/A' }}
                            </p>
                            <p>
                                <span class="font-semibold">Especialidad:</span>
                                {{ $doctor->specialty->name }}
                            </p>
                            <p>
                                <span class="font-semibold">Biografía:</span>
                                {{ Str::limit($doctor->biography ?? 'N/A', 30) }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex space-x-3 mt-6 lg:mt-0">
                    <x-button outline gray href="{{ route('admin.doctors.index') }}">
                        Volver
                    </x-button>
                    <x-button type="submit">
                        <i class="fa-solid fa-check mr-2"></i>
                        Actualizar Doctor
                    </x-button>
                </div>
            </div>
        </div></div>

        {{-- Formulario de edición --}}
        <div class="mt-8 flow-root bg-white shadow overflow-hidden sm:rounded-lg border border-gray-200"><div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Especialidad --}}
                <div>
                    <x-native-select
                        name="specialty_id"
                        label="Especialidad"
                        :error="$errors->first('specialty_id')"
                    >
                        <option value="">Selecciona una especialidad</option>
                        @foreach($specialties as $specialty)
                            <option value="{{ $specialty->id }}"
                                {{ old('specialty_id', $doctor->specialty_id) == $specialty->id ? 'selected' : '' }}>
                                {{ $specialty->name }}
                            </option>
                        @endforeach
                    </x-native-select>
                </div>

                {{-- Cédula Profesional --}}
                <div>
                    <x-input
                        name="license_number"
                        label="Cédula Profesional"
                        placeholder="Ej: 12345678"
                        :value="old('license_number', $doctor->license_number)"
                        :error="$errors->first('license_number')"
                    />
                </div>

                {{-- Biografía --}}
                <div class="md:col-span-2">
                    <x-textarea
                        name="biography"
                        label="Biografía"
                        placeholder="Breve descripción profesional del doctor..."
                        rows="5"
                        :error="$errors->first('biography')"
                    >
                        {{ old('biography', $doctor->biography) }}
                    </x-textarea>
                </div>
            </div>
        </div></div>
    </form>
</x-admin-layout>
