<?php

namespace App\Livewire\Admin\Appointments;

use Livewire\Component;

use App\Models\Appointment;

class ConsultationManager extends Component
{
    public Appointment $appointment;

    public $diagnosis;
    public $treatment;
    public $notes;
    
    // Tab tracking
    public $activeTab = 'consulta';

    public function mount(Appointment $appointment)
    {
        $this->appointment = $appointment->load('patient.user', 'doctor.user');
        
        // Initial setup if we had a consultations table
        // $this->diagnosis = $this->appointment->consultation?->diagnosis ?? '';
    }

    public function saveConsultation()
    {
        // Simply flag appointment as completed for this phase
        $this->appointment->status = 2; // Completada
        $this->appointment->save();

        session()->flash('success', 'Consulta guardada exitosamente. La cita ha sido completada.');
        return redirect()->route('admin.appointments.index');
    }

    public function render()
    {
        $pastAppointments = Appointment::with(['doctor.user'])
            ->where('patient_id', $this->appointment->patient_id)
            ->where('status', 2) // only completed
            ->where('id', '!=', $this->appointment->id)
            ->orderBy('date', 'desc')
            ->get();

        return view('livewire.admin.appointments.consultation-manager', [
            'pastAppointments' => $pastAppointments
        ])->layout('layouts.admin', ['title' => 'Consulta Médica | Farmacon']);
    }
}
