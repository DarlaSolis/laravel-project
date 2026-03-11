<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $specialties = \App\Models\Specialty::all();
        
        if ($specialties->count() == 0) {
            // No specialties yet, create a default one
            $specialties->push(\App\Models\Specialty::create(['name' => 'General', 'description' => 'Medicina General']));
        }

        // We create 5 doctors
        for ($i = 1; $i <= 5; $i++) {
            $user = \App\Models\User::create([
                'name' => "Doctor Demo {$i}",
                'email' => "doctor{$i}@demo.com",
                'password' => bcrypt('password123'),
                'id_number' => 'DOC-' . rand(10000000, 99999999),
                'phone' => '55' . rand(10000000, 99999999),
                'address' => "Hospital General, Consultorio {$i}",
            ]);

            // Assign doctor role (ID 2 usually, assuming 1 is admin)
            $user->roles()->attach(2);

            \App\Models\Doctor::create([
                'user_id' => $user->id,
                'specialty_id' => $specialties->random()->id,
                'license_number' => "LIC-" . rand(100000, 999999),
                'biography' => "Biografía de prueba para Doctor Demo {$i}",
            ]);
        }
    }
}
