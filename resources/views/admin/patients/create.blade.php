<x-admin-layout title="Nuevo Paciente | Farmacon" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Pacientes', 'href' => route('admin.patients.index')],
    ['name' => 'Nuevo'],
]">

    <form action="{{ route('admin.patients.store') }}" method="POST">
        @csrf

        <div class="mb-8 flex justify-between items-center bg-white p-4 rounded-lg shadow-sm">
            <h2 class="text-xl font-bold text-gray-900">Nuevo Paciente</h2>
            <div class="flex space-x-3">
                <a href="{{ route('admin.patients.index') }}" class="px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancelar
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fa-solid fa-check mr-2"></i>
                    Guardar Paciente
                </button>
            </div>
        </div>

        <div class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg border border-gray-200">
            <div class="p-6">
                <!-- SECCIÓN: DATOS DE USUARIO -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <h3 class="md:col-span-2 text-lg font-semibold text-gray-700 border-b pb-2">Datos del Usuario</h3>

                    <div>
                        <x-input name="name" label="Nombre completo" placeholder="Ej: Juan Pérez" :value="old('name')" :error="$errors->first('name')" required />
                    </div>

                    <div>
                        <x-input name="email" label="Correo electrónico" type="email" placeholder="ejemplo@correo.com" :value="old('email')" :error="$errors->first('email')" required />
                    </div>

                    <div>
                        <x-input name="password" label="Contraseña" type="password" placeholder="Mínimo 8 caracteres" :error="$errors->first('password')" required />
                    </div>

                    <div>
                        <x-input name="password_confirmation" label="Confirmar contraseña" type="password" placeholder="Repite la contraseña" required />
                    </div>

                    <div>
                        <x-input name="phone" label="Teléfono" placeholder="Ej: 9991234567" :value="old('phone')" :error="$errors->first('phone')" />
                    </div>
                    
                    <div>
                        <x-input name="id_number" label="Identificación (CURP/DNI)" placeholder="Ej: 12345678" :value="old('id_number')" :error="$errors->first('id_number')" />
                    </div>

                    <div class="md:col-span-2">
                        <x-input name="address" label="Dirección" placeholder="Ej: Calle Principal #123" :value="old('address')" :error="$errors->first('address')" />
                    </div>
                </div>

                <!-- SECCIÓN: INFORMACIÓN MÉDICA -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-10">
                    <h3 class="md:col-span-2 text-lg font-semibold text-gray-700 border-b pb-2">Información Médica</h3>

                    <div>
                        <x-native-select name="blood_type_id" label="Tipo de Sangre" :error="$errors->first('blood_type_id')">
                            <option value="">Selecciona un tipo de sangre</option>
                            @foreach($bloodTypes as $bloodType)
                                <option value="{{ $bloodType->id }}" {{ old('blood_type_id') == $bloodType->id ? 'selected' : '' }}>
                                    {{ $bloodType->name }}
                                </option>
                            @endforeach
                        </x-native-select>
                    </div>

                    <div>
                        <x-input name="allergies" label="Alergias" placeholder="Ej: Penicilina, polen..." :value="old('allergies')" :error="$errors->first('allergies')" />
                    </div>

                    <div>
                        <x-input name="chronic_conditions" label="Enfermedades crónicas" placeholder="Ej: Diabetes, Hipertensión..." :value="old('chronic_conditions')" :error="$errors->first('chronic_conditions')" />
                    </div>

                    <div>
                        <x-input name="surgical_history" label="Historial quirúrgico" placeholder="Ej: Apendicectomía en 2015" :value="old('surgical_history')" :error="$errors->first('surgical_history')" />
                    </div>

                    <div class="md:col-span-2">
                        <x-input name="family_history" label="Historial familiar" placeholder="Ej: Madre con diabetes" :value="old('family_history')" :error="$errors->first('family_history')" />
                    </div>
                </div>

                <!-- SECCIÓN: CONTACTO DE EMERGENCIA -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-10">
                    <h3 class="md:col-span-2 text-lg font-semibold text-gray-700 border-b pb-2">Contacto de Emergencia</h3>

                    <div class="md:col-span-2">
                        <x-input name="emergency_contact_name" label="Nombre completo del contacto" placeholder="Ej: María Pérez" :value="old('emergency_contact_name')" :error="$errors->first('emergency_contact_name')" />
                    </div>

                    <div>
                        <x-input name="emergency_contact_phone" label="Teléfono de emergencia" placeholder="Ej: 9997654321" :value="old('emergency_contact_phone')" :error="$errors->first('emergency_contact_phone')" />
                    </div>

                    <div>
                        <x-input name="emergency_contact_relationship" label="Parentesco" placeholder="Ej: Madre, Esposo(a)..." :value="old('emergency_contact_relationship')" :error="$errors->first('emergency_contact_relationship')" />
                    </div>
                </div>

            </div>
        </div>
    </form>
</x-admin-layout>
