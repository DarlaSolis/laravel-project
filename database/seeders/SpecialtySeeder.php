<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Specialty;

class SpecialtySeeder extends Seeder
{
    public function run(): void
    {
        $specialties = [
            ['name' => 'Cardiología', 'description' => 'Especialidad médica que se ocupa del corazón'],
            ['name' => 'Pediatría', 'description' => 'Atención médica de niños y adolescentes'],
            ['name' => 'Dermatología', 'description' => 'Tratamiento de enfermedades de la piel'],
            ['name' => 'Ginecología', 'description' => 'Salud del sistema reproductor femenino'],
            ['name' => 'Neurología', 'description' => 'Trastornos del sistema nervioso'],
            ['name' => 'Oftalmología', 'description' => 'Cuidado de los ojos y la visión'],
            ['name' => 'Traumatología', 'description' => 'Lesiones del sistema musculoesquelético'],
            ['name' => 'Psiquiatría', 'description' => 'Salud mental y trastornos emocionales'],
            ['name' => 'Veterinaria', 'description' => 'Especialidad del sistema veterinaria'],
        ];

        foreach ($specialties as $specialty) {
            Specialty::create($specialty);
        }

        $this->command->info('Especialidades creadas correctamente.');
    }
}
