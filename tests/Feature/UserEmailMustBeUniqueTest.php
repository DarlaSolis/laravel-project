<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::create(['id' => 1, 'name' => 'admin']);
    Role::create(['id' => 2, 'name' => 'user']);
});

test('User email must remain unique during update', function () {
    $admin = User::factory()->create(['phone' => '1234567890']); // Teléfono válido
    $admin->assignRole('admin');

    $user1 = User::factory()->create([
        'email' => 'usuario1@ejemplo.com',
        'phone' => '1234567891' // Teléfono válido
    ]);

    $user2 = User::factory()->create([
        'email' => 'usuario2@ejemplo.com',
        'phone' => '1234567892' // Teléfono válido
    ]);

    $this->actingAs($admin);

    $response = $this->put(route('admin.users.update', $user1), [
        'name' => $user1->name,
        'email' => 'usuario2@ejemplo.com', // Email duplicado
        'id_number' => $user1->id_number,
        'phone' => '1234567891', // Mismo teléfono (válido)
        'address' => $user1->address,
        'role_id' => 2,
        'password' => '',
        'password_confirmation' => ''
    ]);

    $response->assertSessionHasErrors(['email']);
    $this->assertDatabaseHas('users', [
        'id' => $user1->id,
        'email' => 'usuario1@ejemplo.com'
    ]);
});

test('User can update to a new unique email', function () {
    $admin = User::factory()->create(['phone' => '1234567890']); // Teléfono válido
    $admin->assignRole('admin');

    $user = User::factory()->create([
        'email' => 'original@ejemplo.com',
        'phone' => '1234567888' // Teléfono válido
    ]);

    $this->actingAs($admin);

    $response = $this->put(route('admin.users.update', $user), [
        'name' => $user->name,
        'email' => 'nuevoemail@ejemplo.com', // Email único
        'id_number' => $user->id_number,
        'phone' => '1234567888', // Mismo teléfono (válido)
        'address' => $user->address,
        'role_id' => 2,
        'password' => '',
        'password_confirmation' => ''
    ]);

    $response->assertSessionHasNoErrors();
    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'email' => 'nuevoemail@ejemplo.com'
    ]);
});
