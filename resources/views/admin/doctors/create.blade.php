<x-admin-layout title="Nuevo Doctor | Farmacon" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Doctores', 'href' => route('admin.doctors.index')],
    ['name' => 'Nuevo'],
]">

    <form action="{{ route('admin.doctors.store') }}" method="POST">
        @csrf

        <x-wire-card class="mb-8">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-900">Nuevo Doctor</h2>
                <div class="flex space-x-3">
                    <x-wire-button outline gray href="{{ route('admin.doctors.index') }}">
                        Cancelar
                    </x-wire-button>
                    <x-wire-button type="submit">
                        <i class="fa-solid fa-check mr-2"></i>
                        Guardar Doctor
                    </x-wire-button>
                </div>
            </div>
        </x-wire-card>

        <x-wire-card>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <h3 class="md:col-span-2 text-lg font-semibold text-gray-700">Datos del Usuario</h3>

                {{-- Nombre --}}
                <div>
                    <x-wire-input
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
                    <x-wire-input
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
                    <x-wire-input
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
                    <x-wire-input
                        name="password_confirmation"
                        label="Confirmar contraseña"
                        type="password"
                        placeholder="Repite la contraseña"
                        required
                    />
                </div>

                {{-- ID Number --}}
                <div>
                    <x-wire-input
                        name="id_number"
                        label="Cédula de identidad"
                        placeholder="Ej: 12345678"
                        :value="old('id_number')"
                        :error="$errors->first('id_number')"
                    />
                </div>

                {{-- Teléfono --}}
                <div>
                    <x-wire-input
                        name="phone"
                        label="Teléfono"
                        placeholder="Ej: 1234567890"
                        :value="old('phone')"
                        :error="$errors->first('phone')"
                    />
                </div>

                {{-- Dirección --}}
                <div class="md:col-span-2">
                    <x-wire-input
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
                    <x-wire-native-select
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
                    </x-wire-native-select>
                </div>

                {{-- Cédula Profesional --}}
                <div>
                    <x-wire-input
                        name="license_number"
                        label="Cédula Profesional"
                        placeholder="Ej: 12345678"
                        :value="old('license_number')"
                        :error="$errors->first('license_number')"
                    />
                </div>

                {{-- Biografía --}}
                <div class="md:col-span-2">
                    <x-wire-textarea
                        name="biography"
                        label="Biografía"
                        placeholder="Breve descripción profesional del doctor..."
                        rows="5"
                        :error="$errors->first('biography')"
                    >
                        {{ old('biography') }}
                    </x-wire-textarea>
                </div>
            </div>
        </x-wire-card>
    </form>
</x-admin-layout>
