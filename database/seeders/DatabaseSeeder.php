<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            SpecialtySeeder::class,
            BloodTypeSeeder::class,
            UserSeeder::class,
            PatientSeeder::class, // Added PatientSeeder
            DoctorSeeder::class, // Added DoctorSeeder for testing appointments
        ]);
    }
}
