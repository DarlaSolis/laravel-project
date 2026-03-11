<?php

namespace App\Livewire\Admin\Appointments;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Appointment;

class AppointmentIndex extends Component
{
    use WithPagination;

    public function delete($id)
    {
        $appointment = Appointment::find($id);
        if ($appointment) {
            $appointment->delete();
            session()->flash('message', 'Cita eliminada correctamente.');
        }
    }

    public function render()
    {
        $appointments = Appointment::with(['patient.user', 'doctor.user'])
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('livewire.admin.appointments.appointment-index', [
            'appointments' => $appointments
        ])->layout('layouts.admin', ['title' => 'Citas Médicas | Farmacon']);
    }
}