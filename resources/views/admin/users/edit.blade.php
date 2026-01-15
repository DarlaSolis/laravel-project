<x-admin-layout title="Editar Usuario | Farmacon" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Usuarios', 'href' => route('admin.users.index')],
    ['name' => 'Editar Usuario'],
]">
    <x-wire-card>
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div class="grid lg:grid-cols-2 gap-4">
                    <x-wire-input
                        name="name"
                        label="Nombre"
                        required :value="old('name', $user->name)"
                        placeholder="Nombre"
                        autocomplete="name"
                    />

                    <x-wire-input
                        name="email"
                        label="Email"
                        required :value="old('email', $user->email)"
                        placeholder="usuario@correo.com"
                        autocomplete="email"
                        inputmode="email"
                    />

                    <x-wire-input
                        name="password"
                        label="Contraseña"
                        type="password"
                        placeholder="Mínimo de 8 caracteres"
                        autocomplete="new-password"
                        inputmode="password"
                    />

                    <x-wire-input
                        name="password_confirmation"
                        label="Confirmar contraseña"
                        type="password"
                        placeholder="Repita la contraseña"
                        autocomplete="new-password"
                        inputmode="password"
                    />

                    <x-wire-input
                        name="id_number"
                        label="Número de ID"
                        required :value="old('id_number', $user->id_number)"
                        placeholder="Ej. 12345678"
                        autocomplete="off"
                        inputmode="numeric"
                    />

                    <x-wire-input
                        name="phone"
                        label="Teléfono"
                        required :value="old('phone', $user->phone)"
                        placeholder="Ej. 123456789"
                        autocomplete="tel"
                        inputmode="tel"
                    />
                </div>

                <x-wire-input
                    name="address"
                    label="Dirección"
                    required :value="old('address', $user->address)"
                    placeholder="Ej. Calle 123"
                    autocomplete="street-address"
                />
            </div>

            <div class="space-y-1">
                <x-wire-native-select
                    name="role_id"
                    label="Rol"
                    required>
                    <option value="">
                        Seleccione un rol
                    </option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}" @selected(old('role_id', $user->roles->first()->id) == $role->id)>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </x-wire-native-select>

                <p class="text-sm text-gray-500">
                    Define los permisos y accesos del usuario
                </p>

                <div class="flex justify-end">
                    <x-wire-button type="submit">
                        Actualizar
                    </x-wire-button>
                </div>
            </div>
        </form>
    </x-wire-card>
</x-admin-layout>
