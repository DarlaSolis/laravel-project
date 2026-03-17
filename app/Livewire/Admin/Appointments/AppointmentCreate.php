<?php

namespace App\Livewire\Admin\Appointments;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Specialty;
use Livewire\Component;

class AppointmentCreate extends Component
{
    // Search Filters
    public $searchDate;
    public $searchTime;
    public $searchSpecialty;

    // Selected Appointment Data
    public $doctor_id;
    public $patient_id;
    public $date;
    public $start_time;
    public $end_time;
    public $reason;
    public $selectedDoctorName;

    // Available doctors and their slots
    public $availableDoctors = [];

    public function mount()
    {
        $this->searchDate = now()->format('Y-m-d');
        $this->searchAvailability();
    }

    public function rules()
    {
        return [
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'reason' => 'required|string|max:500'
        ];
    }

    public function searchAvailability()
    {
        $this->validate([
            'searchDate' => 'required|date|after_or_equal:today',
        ]);

        $carbonDate = \Carbon\Carbon::parse($this->searchDate);
        $dayOfWeek = $carbonDate->dayOfWeekIso; // 1 = Monday, ..., 7 = Sunday

        $query = Doctor::with(['user', 'specialty', 'schedules' => function ($q) use ($dayOfWeek) {
            $q->where('day_of_week', $dayOfWeek);
        }]);

        if ($this->searchSpecialty) {
            $query->where('specialty_id', $this->searchSpecialty);
        }

        $allDoctors = $query->get();
        $this->availableDoctors = [];

        foreach ($allDoctors as $doctor) {
            $schedule = $doctor->schedules->first();
            if ($schedule && !empty($schedule->available_slots)) {
                
                // Get pre-booked appointments for this doctor on this exact date
                $bookedSlots = Appointment::where('doctor_id', $doctor->id)
                    ->whereDate('date', $this->searchDate)
                    ->where('status', '!=', 3) // Not cancelled
                    ->pluck('start_time')
                    ->map(function ($time) {
                        return \Carbon\Carbon::parse($time)->format('H:i');
                    })
                    ->toArray();

                $freeSlots = array_values(array_filter($schedule->available_slots, function($slot) use ($bookedSlots, $carbonDate) {
                    // Also filter out past times if the selected date is today
                    if ($carbonDate->isToday()) {
                        $slotTime = \Carbon\Carbon::createFromFormat('H:i', $slot);
                        if ($slotTime->isPast()) {
                            return false;
                        }
                    }
                    return !in_array($slot, $bookedSlots);
                }));

                // If a specific time range is searched, filter out slots not conforming to it
                if ($this->searchTime) {
                    $parts = explode(' - ', $this->searchTime);
                    if (count($parts) === 2) {
                        $searchStart = \Carbon\Carbon::createFromFormat('H:i:s', $parts[0])->format('H:i');
                        $searchEnd = \Carbon\Carbon::createFromFormat('H:i:s', $parts[1])->format('H:i');
                        
                        $freeSlots = array_values(array_filter($freeSlots, function($slot) use ($searchStart, $searchEnd) {
                            return $slot >= $searchStart && $slot < $searchEnd;
                        }));
                    }
                }

                if (count($freeSlots) > 0) {
                    $this->availableDoctors[] = [
                        'doctor' => $doctor,
                        'slots' => $freeSlots
                    ];
                }
            }
        }

        // Reset selections if the filters change
        if ($this->date !== $this->searchDate) {
            $this->resetSelection();
        }
    }

    public function selectSlot($doctorId, $doctorName, $startTime)
    {
        $this->doctor_id = $doctorId;
        $this->selectedDoctorName = $doctorName;
        $this->date = $this->searchDate;
        $this->start_time = $startTime;
        $this->end_time = \Carbon\Carbon::createFromFormat('H:i', $startTime)->addMinutes(15)->format('H:i');
    }

    public function resetSelection()
    {
        $this->doctor_id = null;
        $this->selectedDoctorName = null;
        $this->date = null;
        $this->start_time = null;
        $this->end_time = null;
    }

    public function save()
    {
        $this->validate();

        Appointment::create([
            'patient_id' => $this->patient_id,
            'doctor_id' => $this->doctor_id,
            'date' => $this->date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'duration' => 15,
            'reason' => $this->reason,
            'status' => 1 // Pendiente
        ]);

        session()->flash('success', 'Cita médica creada exitosamente.');
        return redirect()->route('admin.appointments.index');
    }

    public function render()
    {
        return view('livewire.admin.appointments.appointment-create', [
            'patients' => Patient::with('user')->get(),
            'specialties' => Specialty::all(),
            'timeRanges' => [
                '08:00:00 - 09:00:00',
                '09:00:00 - 10:00:00',
                '10:00:00 - 11:00:00',
                '11:00:00 - 12:00:00',
                '12:00:00 - 13:00:00',
                '13:00:00 - 14:00:00',
                '14:00:00 - 15:00:00',
                '15:00:00 - 16:00:00',
                '16:00:00 - 17:00:00',
                '17:00:00 - 18:00:00',
            ]
        ])->layout('layouts.admin', ['title' => 'Nueva Cita | Farmacon']);
    }
}
