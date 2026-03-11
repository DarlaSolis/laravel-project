<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $patients = \App\Models\Patient::all();
        $doctors = \App\Models\Doctor::all();

        if ($patients->isEmpty() || $doctors->isEmpty()) {
            $this->command->info('No hay pacientes o doctores para crear citas. Ejecuta primero PatientSeeder y DoctorSeeder.');
            return;
        }

        $statuses = [1, 2, 3]; // 1: Pendiente, 2: Atendida, 3: Cancelada

        foreach ($patients as $patient) {
            // Generar 3 citas aleatorias por paciente
            for ($i = 0; $i < 3; $i++) {
                $doctor = $doctors->random();
                
                // Distribución aleatoria de fechas: Desde hace 30 días hasta dentro de 15 días
                $date = \Carbon\Carbon::now()->addDays(rand(-30, 15));
                $startHour = rand(8, 17);
                $startMinute = array_rand([0 => '00', 1 => '15', 2 => '30', 3 => '45']); // 15-min intervals
                $minutes = ['00', '15', '30', '45'][$startMinute];
                
                $startTime = sprintf('%02d:%s', $startHour, $minutes);
                $endTime = \Carbon\Carbon::createFromFormat('H:i', $startTime)->addMinutes(15)->format('H:i');

                // Si la fecha es pasada, la cita no puede estar pendiente. 
                // Forzamos que sea Atendida (2) o Cancelada (3).
                $status = $statuses[array_rand($statuses)];
                if ($date->isPast() && $status == 1) {
                    $status = rand(2, 3);
                }

                // Motivos de prueba
                $reasons = [
                    'Chequeo general de rutina.',
                    'Dolor de cabeza constante por 3 días.',
                    'Seguimiento de tratamiento anterior.',
                    'Renovación de receta médica.',
                    'Resultados de análisis de laboratorio.',
                    'Dolor abdominal persistente.',
                ];

                \App\Models\Appointment::create([
                    'patient_id' => $patient->id,
                    'doctor_id' => $doctor->id,
                    'date' => $date->format('Y-m-d'),
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'duration' => 15,
                    'reason' => $reasons[array_rand($reasons)],
                    'status' => $status
                ]);
            }
        }

        $this->command->info('Citas de prueba generadas exitosamente para los pacientes.');
    }
}
