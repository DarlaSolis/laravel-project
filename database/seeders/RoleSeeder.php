<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Definir roles
        $roles = [
            'Paciente',
            'Doctor',
            'Recepcionista',
            'Administrador'
        ];

        // Crear en la BD solo si no existen
        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role], // Condición para buscar
                ['name' => $role]   // Datos para crear si no existe
            );
        }
    }
}
