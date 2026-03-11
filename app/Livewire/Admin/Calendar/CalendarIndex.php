<?php

namespace App\Livewire\Admin\Calendar;

use Livewire\Component;

class CalendarIndex extends Component
{
    public function render()
    {
        $appointments = \App\Models\Appointment::with(['patient.user', 'doctor.user'])->get();
        $events = [];

        foreach ($appointments as $app) {
            $events[] = [
                'id' => $app->id,
                'title' => \Carbon\Carbon::parse($app->start_time)->format('H:i') . ' ' . $app->patient->user->name,
                'start' => $app->date . 'T' . $app->start_time,
                'end' => $app->date . 'T' . $app->end_time,
                // Si la cita está Cancelada (estado 3) o Atendida (estado 2) le damos un color, si es Pendiente (1) es azul.
                'backgroundColor' => $app->status === 1 ? '#3b82f6' : ($app->status === 2 ? '#10b981' : '#ef4444'),
                'borderColor' => $app->status === 1 ? '#2563eb' : ($app->status === 2 ? '#059669' : '#dc2626'),
                'url' => route('admin.appointments.consultation', $app->id),
                'extendedProps' => [
                    'doctor' => 'Dr(a). ' . $app->doctor->user->name
                ]
            ];
        }

        return view('livewire.admin.calendar.calendar-index', [
            'events' => json_encode($events)
        ])->layout('layouts.admin', ['title' => 'Calendario | Farmacon']);
    }
}
