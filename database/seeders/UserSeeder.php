<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Usuario Administrador - NOTA: en tu RoleSeeder es 'Administrador' (con 'r' al final)
        $admin = User::updateOrCreate(
            ['email' => 'admin@correo.com'],
            [
                'name' => 'Administrador',
                'password' => bcrypt('123456789'),
                'id_number' => '000000001',
                'phone' => '1111111111',
                'address' => 'Dirección Admin',
            ]
        );

        // Asignar rol Administrador (nombre exacto de tu RoleSeeder)
        $adminRole = Role::where('name', 'Administrador')->first();
        if ($adminRole) {
            $admin->roles()->sync([$adminRole->id]);
        }

        // Usuario Doctor
        $doctor = User::updateOrCreate(
            ['email' => 'darla@correo.com'],
            [
                'name' => 'Darla Solis',
                'password' => bcrypt('123456789'),
                'id_number' => '123456789',
                'phone' => '1234567890',
                'address' => 'Calle No. 23',
            ]
        );

        // Asignar rol Doctor
        $doctorRole = Role::where('name', 'Doctor')->first();
        if ($doctorRole) {
            $doctor->roles()->sync([$doctorRole->id]);
            
            // Asegurarnos de que su registro exista en la tabla doctors
            $specialty = \App\Models\Specialty::first();
            if($specialty){
                \App\Models\Doctor::firstOrCreate(
                    ['user_id' => $doctor->id],
                    [
                        'specialty_id' => $specialty->id,
                        'license_number' => 'LIC-123456',
                        'biography' => 'Especialista principal.',
                    ]
                );
            }
        }

        // Opcional: Crear otros usuarios si quieres probar todos los roles
        $recepcionista = User::updateOrCreate(
            ['email' => 'recepcion@correo.com'],
            [
                'name' => 'Recepcionista',
                'password' => bcrypt('123456789'),
                'id_number' => '000000002',
                'phone' => '2222222222',
                'address' => 'Dirección Recepción',
            ]
        );

        $recepcionistaRole = Role::where('name', 'Recepcionista')->first();
        if ($recepcionistaRole) {
            $recepcionista->roles()->sync([$recepcionistaRole->id]);
        }

        $paciente = User::updateOrCreate(
            ['email' => 'paciente@correo.com'],
            [
                'name' => 'Juan Pérez',
                'password' => bcrypt('123456789'),
                'id_number' => '000000003',
                'phone' => '3333333333',
                'address' => 'Dirección Paciente',
            ]
        );

        $pacienteRole = Role::where('name', 'Paciente')->first();
        if ($pacienteRole) {
            $paciente->roles()->sync([$pacienteRole->id]);
            
            // Crear el registro de paciente asociado
            $bloodType = \App\Models\BloodType::first();
            if($bloodType){
                \App\Models\Patient::firstOrCreate(
                    ['user_id' => $paciente->id],
                    [
                        'blood_type_id' => $bloodType->id,
                        'emergency_contact_name' => 'Maria Perez',
                        'emergency_contact_phone' => '5555555555',
                        'allergies' => 'Ninguna',
                        'chronic_conditions' => 'Ninguna',
                        'surgical_history' => 'Ninguno',
                    ]
                );
            }
        }
    }
}
