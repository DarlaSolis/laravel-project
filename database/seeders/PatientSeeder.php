<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bloodTypes = \App\Models\BloodType::all();
        
        if ($bloodTypes->count() == 0) {
            $bloodTypes->push(\App\Models\BloodType::create(['name' => 'O+']));
        }

        // We create 5 patients
        for ($i = 1; $i <= 5; $i++) {
            $user = \App\Models\User::create([
                'name' => "Paciente Demo {$i}",
                'email' => "paciente{$i}@demo.com",
                'password' => bcrypt('password123'),
                'id_number' => 'PAC-' . rand(10000000, 99999999),
                'phone' => '44' . rand(10000000, 99999999),
                'address' => "Calle Principal, Casa {$i}",
            ]);

            // Assign patient role (ID 4 usually, assuming order: 1=Admin, 2=Doctor, 3=Receptionist, 4=Patient)
            // Let's find it by name to be safe
            $role = \Spatie\Permission\Models\Role::where('name', 'Paciente')->first();
            if ($role) {
                $user->roles()->attach($role->id);
            }

            \App\Models\Patient::create([
                'user_id' => $user->id,
                'blood_type_id' => $bloodTypes->random()->id,
                'emergency_contact_name' => "Contacto de Demo",
                'emergency_contact_phone' => '888' . rand(1000000, 9999999),
                'allergies' => 'Ninguna',
                'chronic_conditions' => 'Ninguna',
                'surgical_history' => 'Ninguno',
            ]);
        }
    }
}
