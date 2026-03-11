<x-admin-layout title="Nuevo Doctor | Farmacon" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Doctores', 'href' => route('admin.doctors.index')],
    ['name' => 'Nuevo'],
]">

    <form action="{{ route('admin.doctors.store') }}" method="POST">
        @csrf

        <x-card class="mb-8">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-900">Nuevo Doctor</h2>
                <div class="flex space-x-3">
                    <x-button outline gray href="{{ route('admin.doctors.index') }}">
                        Cancelar
                    </x-button>
                    <x-button type="submit">
                        <i class="fa-solid fa-check mr-2"></i>
                        Guardar Doctor
                    </x-button>
                </div>
            </div>
        </div></div>

        <div class="mt-8 flow-root bg-white shadow overflow-hidden sm:rounded-lg border border-gray-200"><div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <h3 class="md:col-span-2 text-lg font-semibold text-gray-700">Datos del Usuario</h3>

                {{-- Nombre --}}
                <div>
                    <x-input
                        name="name"
                        label="Nombre completo"
                        placeholder="Ej: Dr. Juan Pérez"
                        :value="old('name')"
                        :error="$errors->first('name')"
                        required
                    />
                </div>

                {{-- Email --}}
                <div>
                    <x-input
                        name="email"
                        label="Correo electrónico"
                        type="email"
                        placeholder="ejemplo@correo.com"
                        :value="old('email')"
                        :error="$errors->first('email')"
                        required
                    />
                </div>

                {{-- Contraseña --}}
                <div>
                    <x-input
                        name="password"
                        label="Contraseña"
                        type="password"
                        placeholder="Mínimo 8 caracteres"
                        :error="$errors->first('password')"
                        required
                    />
                </div>

                {{-- Confirmar contraseña --}}
                <div>
                    <x-input
                        name="password_confirmation"
                        label="Confirmar contraseña"
                        type="password"
                        placeholder="Repite la contraseña"
                        required
                    />
                </div>

                {{-- ID Number --}}
                <div>
                    <x-input
                        name="id_number"
                        label="Cédula de identidad"
                        placeholder="Ej: 12345678"
                        :value="old('id_number')"
                        :error="$errors->first('id_number')"
                    />
                </div>

                {{-- Teléfono --}}
                <div>
                    <x-input
                        name="phone"
                        label="Teléfono"
                        placeholder="Ej: 1234567890"
                        :value="old('phone')"
                        :error="$errors->first('phone')"
                    />
                </div>

                {{-- Dirección --}}
                <div class="md:col-span-2">
                    <x-input
                        name="address"
                        label="Dirección"
                        placeholder="Ej: Calle Principal #123"
                        :value="old('address')"
                        :error="$errors->first('address')"
                    />
                </div>

                <div class="md:col-span-2 border-t border-gray-200 my-4"></div>

                <h3 class="md:col-span-2 text-lg font-semibold text-gray-700">Datos Profesionales</h3>

                {{-- Especialidad --}}
                <div>
                    <x-native-select
                        name="specialty_id"
                        label="Especialidad"
                        :error="$errors->first('specialty_id')"
                        required
                    >
                        <option value="">Selecciona una especialidad</option>
                        @foreach($specialties as $specialty)
                            <option value="{{ $specialty->id }}"
                                {{ old('specialty_id') == $specialty->id ? 'selected' : '' }}>
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
                        :value="old('license_number')"
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
                        {{ old('biography') }}
                    </x-textarea>
                </div>
            </div>
        </div></div>
    </form>
</x-admin-layout>
