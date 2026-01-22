<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Crear roles necesarios con IDs específicos
    Role::create(['id' => 1, 'name' => 'admin']);
    Role::create(['id' => 2, 'name' => 'user']);
});

test('Phone number must contain only digits', function () {
    // 1. Crear admin con teléfono válido
    $admin = User::factory()->create(['phone' => '1234567890']);
    $admin->assignRole('admin');

    // 2. Autenticar como admin
    $this->actingAs($admin);

    // 3. Intentar crear usuario con teléfono que contiene letras
    $response = $this->post(route('admin.users.store'), [
        'name' => 'Usuario Test',
        'email' => 'test@ejemplo.com',
        'password' => 'Password123',
        'password_confirmation' => 'Password123',
        'id_number' => 'TEST123',
        'phone' => 'abc123def', // ¡INVÁLIDO! Contiene letras
        'address' => 'Dirección Test',
        'role_id' => 2, // ID del rol 'user'
    ]);

    // 4. Debería mostrar error de validación
    $response->assertSessionHasErrors(['phone']);
    $this->assertDatabaseMissing('users', ['email' => 'test@ejemplo.com']);
});

test('Phone number must be between 7 and 15 digits - too short', function () {
    // 1. Crear admin con teléfono válido
    $admin = User::factory()->create(['phone' => '1234567890']);
    $admin->assignRole('admin');

    // 2. Autenticar como admin
    $this->actingAs($admin);

    // 3. Intentar crear usuario con teléfono muy corto (solo 3 dígitos)
    $response = $this->post(route('admin.users.store'), [
        'name' => 'Usuario Corto',
        'email' => 'corto@ejemplo.com',
        'password' => 'Password123',
        'password_confirmation' => 'Password123',
        'id_number' => 'TEST124',
        'phone' => '123', // ¡INVÁLIDO! Solo 3 dígitos (mínimo 7)
        'address' => 'Dirección Test',
        'role_id' => 2,
    ]);

    // 4. Debería mostrar error de validación
    $response->assertSessionHasErrors(['phone']);
    $this->assertDatabaseMissing('users', ['email' => 'corto@ejemplo.com']);
});

test('Phone number must be between 7 and 15 digits - too long', function () {
    // 1. Crear admin con teléfono válido
    $admin = User::factory()->create(['phone' => '1234567890']);
    $admin->assignRole('admin');

    // 2. Autenticar como admin
    $this->actingAs($admin);

    // 3. Intentar crear usuario con teléfono muy largo (16 dígitos)
    $response = $this->post(route('admin.users.store'), [
        'name' => 'Usuario Largo',
        'email' => 'largo@ejemplo.com',
        'password' => 'Password123',
        'password_confirmation' => 'Password123',
        'id_number' => 'TEST125',
        'phone' => '1234567890123456', // ¡INVÁLIDO! 16 dígitos (máximo 15)
        'address' => 'Dirección Test',
        'role_id' => 2,
    ]);

    // 4. Debería mostrar error de validación
    $response->assertSessionHasErrors(['phone']);
    $this->assertDatabaseMissing('users', ['email' => 'largo@ejemplo.com']);
});

test('Valid phone number format is accepted - 7 digits', function () {
    // 1. Crear admin con teléfono válido
    $admin = User::factory()->create(['phone' => '1234567890']);
    $admin->assignRole('admin');

    // 2. Autenticar como admin
    $this->actingAs($admin);

    // 3. Crear usuario con teléfono válido (7 dígitos - mínimo permitido)
    $response = $this->post(route('admin.users.store'), [
        'name' => 'Usuario 7 Dígitos',
        'email' => 'siete@ejemplo.com',
        'password' => 'Password123',
        'password_confirmation' => 'Password123',
        'id_number' => 'TEST126',
        'phone' => '1234567', // VÁLIDO - 7 dígitos (mínimo)
        'address' => 'Dirección Test',
        'role_id' => 2,
    ]);

    // 4. No debería haber errores
    $response->assertSessionHasNoErrors();

    // 5. Debería crear el usuario
    $this->assertDatabaseHas('users', [
        'email' => 'siete@ejemplo.com',
        'phone' => '1234567'
    ]);
});

test('Valid phone number format is accepted - 15 digits', function () {
    // 1. Crear admin con teléfono válido
    $admin = User::factory()->create(['phone' => '1234567890']);
    $admin->assignRole('admin');

    // 2. Autenticar como admin
    $this->actingAs($admin);

    // 3. Crear usuario con teléfono válido (15 dígitos - máximo permitido)
    $response = $this->post(route('admin.users.store'), [
        'name' => 'Usuario 15 Dígitos',
        'email' => 'quince@ejemplo.com',
        'password' => 'Password123',
        'password_confirmation' => 'Password123',
        'id_number' => 'TEST127',
        'phone' => '123456789012345', // VÁLIDO - 15 dígitos (máximo)
        'address' => 'Dirección Test',
        'role_id' => 2,
    ]);

    // 4. No debería haber errores
    $response->assertSessionHasNoErrors();

    // 5. Debería crear el usuario
    $this->assertDatabaseHas('users', [
        'email' => 'quince@ejemplo.com',
        'phone' => '123456789012345'
    ]);
});

test('Valid phone number format is accepted - 10 digits', function () {
    // 1. Crear admin con teléfono válido
    $admin = User::factory()->create(['phone' => '1234567890']);
    $admin->assignRole('admin');

    // 2. Autenticar como admin
    $this->actingAs($admin);

    // 3. Crear usuario con teléfono válido (10 dígitos - caso común)
    $response = $this->post(route('admin.users.store'), [
        'name' => 'Usuario 10 Dígitos',
        'email' => 'diez@ejemplo.com',
        'password' => 'Password123',
        'password_confirmation' => 'Password123',
        'id_number' => 'TEST128',
        'phone' => '1234567890', // VÁLIDO - 10 dígitos
        'address' => 'Dirección Test',
        'role_id' => 2,
    ]);

    // 4. No debería haber errores
    $response->assertSessionHasNoErrors();

    // 5. Debería crear el usuario
    $this->assertDatabaseHas('users', [
        'email' => 'diez@ejemplo.com',
        'phone' => '1234567890'
    ]);
});
