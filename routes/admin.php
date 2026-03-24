<?php

use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get("/", function () {
    return view('admin.dashboard');
})->name('dashboard');

// Gestión de roles
Route::resource("roles", RoleController::class);

// Gestión de usuarios
Route::resource("users", UserController::class);

//Gestión de pacientes
Route::resource('patients', PatientController::class);

// Gestión de Citas (Appointments)
Route::get('appointments', \App\Livewire\Admin\Appointments\AppointmentIndex::class)->name('appointments.index');
Route::get('appointments/create', \App\Livewire\Admin\Appointments\AppointmentCreate::class)->name('appointments.create');
Route::get('appointments/{appointment}/consultation', \App\Livewire\Admin\Appointments\ConsultationManager::class)->name('appointments.consultation');
