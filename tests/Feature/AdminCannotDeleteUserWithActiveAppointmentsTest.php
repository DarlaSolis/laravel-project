<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Crear roles necesarios
    Role::create(['name' => 'admin']);
    Role::create(['name' => 'user']);
});

test('Admin cannot delete themselves', function () {
    // 1. Crear admin
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    // 2. Autenticar como admin
    $this->actingAs($admin);

    // 3. Intentar eliminarse a sí mismo
    $response = $this->delete(route('admin.users.destroy', $admin));

    // 4. Debería devolver error 403 (por el abort(403) en tu controlador)
    $response->assertStatus(403);

    // 5. El admin debería seguir existiendo
    $this->assertDatabaseHas('users', ['id' => $admin->id]);
});

test('Admin can delete another user', function () {
    // 1. Crear admin
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    // 2. Crear otro usuario
    $otherUser = User::factory()->create();

    // 3. Autenticar como admin
    $this->actingAs($admin);

    // 4. Intentar eliminar al otro usuario
    $response = $this->delete(route('admin.users.destroy', $otherUser));

    // 5. Debería redirigir (éxito)
    $response->assertRedirect(route('admin.users.index'));

    // 6. El otro usuario debería eliminarse
    $this->assertDatabaseMissing('users', ['id' => $otherUser->id]);

    // 7. El admin debería seguir existiendo
    $this->assertDatabaseHas('users', ['id' => $admin->id]);
});
