<?php
use App\Models\User;
use App\Models\Patient;
use App\Models\Doctor;

$emailPatient = 'darladan001+paciente@gmail.com';
$emailAdmin = 'darladan001+admin@gmail.com';

// Update Selena
$selenas = User::where('name', 'like', '%Selena%')->get();
foreach ($selenas as $selena) {
    if (!$selena->hasRole('admin')) {
        $selena->update(['email' => $emailPatient]);
        echo "Updated patient Selena's email to {$emailPatient}\n";
    }
}

// Update Admin
$admins = User::whereHas('roles', function($q) { $q->where('name', 'LIKE', '%admin%'); })->get();
if ($admins->isEmpty()) {
    $admin = User::find(1);
    if ($admin) {
        $admin->update(['email' => $emailAdmin]);
        echo "Updated User ID 1 (Fallback Admin) email to {$emailAdmin}\n";
    }
} else {
    foreach ($admins as $admin) {
        $admin->update(['email' => $emailAdmin]);
        echo "Updated Admin {$admin->name}'s email to {$emailAdmin}\n";
    }
}

// Update Doctors with unique alias
$doctors = Doctor::with('user')->get();
foreach ($doctors as $doctor) {
    if ($doctor->user) {
        $emailDoctor = "darladan001+doctor{$doctor->id}@gmail.com";
        $doctor->user->update(['email' => $emailDoctor]);
        echo "Updated Doctor {$doctor->user->name}'s email to {$emailDoctor}\n";
    }
}

echo "Done modifying test data.\n";
