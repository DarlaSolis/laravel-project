<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Datatables\RoleTable;
use App\Http\Controllers\Admin\RoleController;

Route::redirect('/', '/admin');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Ruta del perfil
    Route::get('/user/profile', [\Laravel\Jetstream\Http\Controllers\Livewire\UserProfileController::class, 'show'])
        ->name('profile.show');
    // Rutas de administración
    Route::prefix('admin')->name('admin.')->group(function () {
        // Cambia esta línea para usar Livewire en lugar del controlador

        Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');

    });
});
