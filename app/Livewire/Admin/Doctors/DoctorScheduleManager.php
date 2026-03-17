<?php

namespace App\Livewire\Admin\Doctors;

use App\Models\Doctor;
use App\Models\DoctorSchedule;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class DoctorScheduleManager extends Component
{
    use WireUiActions;

    public Doctor $doctor;

    // Available days (1 = Monday ... 7 = Sunday)
    public $days = [
        1 => 'LUNES',
        2 => 'MARTES',
        3 => 'MIÉRCOLES',
        4 => 'JUEVES',
        5 => 'VIERNES',
        6 => 'SÁBADO',
        7 => 'DOMINGO'
    ];

    // Array to hold the selected blocks: $selectedSlots[day][time] = true
    public $selectedSlots = [];

    // The start and end time of the work day grid
    public $startTime = '08:00';
    public $endTime = '18:00';

    public function mount(Doctor $doctor)
    {
        $this->doctor = $doctor;
        $this->loadExistingSchedules();
    }

    public function loadExistingSchedules()
    {
        $schedules = $this->doctor->schedules;
        
        $allSlots = $this->generateTimeSlots();

        foreach ($this->days as $day => $name) {
            $this->selectedSlots[$day] = [];
            foreach ($allSlots as $slot) {
                $this->selectedSlots[$day][$slot] = false;
            }
        }

        foreach ($schedules as $schedule) {
            $day = $schedule->day_of_week;
            if (isset($this->selectedSlots[$day])) {
                $slots = $schedule->available_slots ?? [];
                foreach ($slots as $slot) {
                    $this->selectedSlots[$day][$slot] = true;
                }
            }
        }
    }

    public function generateTimeSlots()
    {
        $slots = [];
        $start = \Carbon\Carbon::createFromFormat('H:i', $this->startTime);
        $end = \Carbon\Carbon::createFromFormat('H:i', $this->endTime);

        while ($start < $end) {
            $slots[] = $start->format('H:i');
            $start->addMinutes(15);
        }

        return $slots;
    }

    // Toggle all slots for the entire matrix
    public function toggleEverything()
    {
        $allSlots = $this->generateTimeSlots();
        $allSelected = true;

        foreach ($this->days as $day => $name) {
            foreach ($allSlots as $slot) {
                if (!isset($this->selectedSlots[$day][$slot]) || !$this->selectedSlots[$day][$slot]) {
                    $allSelected = false;
                    break 2; // Break out of both loops
                }
            }
        }

        foreach ($this->days as $day => $name) {
            foreach ($allSlots as $slot) {
                $this->selectedSlots[$day][$slot] = !$allSelected;
            }
        }
    }

    // Toggle all slots for a specific day
    public function toggleFullDay($day)
    {
        $allSlots = $this->generateTimeSlots();
        
        $allSelected = true;
        foreach ($allSlots as $slot) {
            if (!isset($this->selectedSlots[$day][$slot]) || !$this->selectedSlots[$day][$slot]) {
                $allSelected = false;
                break;
            }
        }

        foreach ($allSlots as $slot) {
            $this->selectedSlots[$day][$slot] = !$allSelected;
        }
    }

    public function saveSchedules()
    {
        // Delete all existings for a clean rewrite
        $this->doctor->schedules()->delete();

        foreach ($this->selectedSlots as $day => $slots) {
            $activeSlots = [];
            foreach ($slots as $time => $isActive) {
                if (filter_var($isActive, FILTER_VALIDATE_BOOLEAN)) {
                    $activeSlots[] = $time;
                }
            }

            if (count($activeSlots) > 0) {
                // Sort the times numerically
                sort($activeSlots);
                
                DoctorSchedule::create([
                    'doctor_id' => $this->doctor->id,
                    'day_of_week' => $day,
                    'available_slots' => $activeSlots,
                ]);
            }
        }

        $this->notification()->success(
            $title = '¡Éxito!',
            $description = 'Los horarios del doctor han sido guardados correctamente.'
        );
    }

    public function generateGroupedTimeSlots()
    {
        $grouped = [];
        $start = \Carbon\Carbon::createFromFormat('H:i', $this->startTime);
        $end = \Carbon\Carbon::createFromFormat('H:i', $this->endTime);

        while ($start < $end) {
            $hourString = $start->format('H:00');
            $slotString = $start->format('H:i');
            
            $endTimeSlot = $start->copy()->addMinutes(15)->format('H:i');
            
            if (!isset($grouped[$hourString])) {
                $grouped[$hourString] = [];
            }

            $grouped[$hourString][] = [
                'value' => $slotString,
                'label' => $slotString . ' - ' . $endTimeSlot
            ];

            $start->addMinutes(15);
        }

        return $grouped;
    }

    public function render()
    {
        return view('livewire.admin.doctors.doctor-schedule-manager', [
            'groupedTimeSlots' => $this->generateGroupedTimeSlots()
        ])->layout('layouts.admin');
    }
}
