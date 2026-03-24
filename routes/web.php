<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Datatables\RoleTable;
use App\Http\Controllers\Admin\RoleController;
use App\Livewire\Admin\Appointments\AppointmentIndex;
use App\Livewire\Admin\Appointments\AppointmentCreate;
use App\Livewire\Admin\Appointments\ConsultationManager;

Route::redirect('/', '/admin');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Ruta del perfil
    Route::get('/user/profile', [\Laravel\Jetstream\Http\Controllers\Livewire\UserProfileController::class , 'show'])
        ->name('profile.show');
    // Rutas de administración
    Route::prefix('admin')->name('admin.')->group(function () {
            // Cambia esta línea para usar Livewire en lugar del controlador
    
            Route::get('/roles', [RoleController::class , 'index'])->name('roles.index');

            //RUTA PARA DOCTORES
            Route::resource('doctors', App\Http\Controllers\Admin\DoctorController::class);

            // Ruta para horarios del doctor (evaluación)
            Route::get('doctors/{doctor}/schedules', \App\Livewire\Admin\Doctors\DoctorScheduleManager::class)->name('doctors.schedules');

        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');

        // Rutas para soporte técnico (Tickets)
        Route::resource('tickets', App\Http\Controllers\TicketController::class);

        // Rutas para Citas Médicas
        Route::get('appointments', App\Livewire\Admin\Appointments\AppointmentIndex::class)->name('appointments.index');
        Route::get('appointments/create', App\Livewire\Admin\Appointments\AppointmentCreate::class)->name('appointments.create');
        Route::get('appointments/{appointment}/consultation', App\Livewire\Admin\Appointments\ConsultationManager::class)->name('appointments.consultation');

        // Ruta para el Calendario
        Route::get('calendar', App\Livewire\Admin\Calendar\CalendarIndex::class)->name('calendar.index');
    });
});
