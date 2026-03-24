<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Models\User;
use App\Models\Doctor;
use App\Mail\DailyAdminReport;
use App\Mail\DailyDoctorReport;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendDailyAppointmentsReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-daily-appointments-reports';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends the daily schedule PDF to administrators and doctors';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();
        
        // Fetch all appointments for today
        $appointments = Appointment::with(['patient.user', 'doctor.user', 'doctor.specialty'])
            ->whereDate('date', $today)
            ->get();
            
        // Admin Report
        try {
            // Find admin user(s) - using spatie roles if available
            $admins = User::whereHas('roles', function($q) { 
                $q->where('name', 'LIKE', '%admin%'); 
            })->get();
            
            // If no admins found with role, fallback to user ID 1
            if ($admins->isEmpty()) {
                $admin = User::find(1);
                if ($admin) {
                    $admins->push($admin);
                }
            }
            
            foreach ($admins as $admin) {
                if ($admin->email) {
                    Mail::to($admin->email)->send(new DailyAdminReport($appointments, $today));
                }
            }
            $this->info('Admin report sent.');
        } catch (\Exception $e) {
            $this->error('Failed to send admin report: ' . $e->getMessage());
            Log::error('Admin Report Error: ' . $e->getMessage());
        }

        // Doctor Reports
        $appointmentsByDoctor = $appointments->groupBy('doctor_id');

        foreach ($appointmentsByDoctor as $doctorId => $doctorAppointments) {
            try {
                $doctor = Doctor::with('user')->find($doctorId);
                if ($doctor && $doctor->user && $doctor->user->email) {
                    Mail::to($doctor->user->email)->send(new DailyDoctorReport($doctor, $doctorAppointments, $today));
                    $this->info("Report sent to doctor ID {$doctorId}.");
                }
            } catch (\Exception $e) {
                $this->error("Failed to send report to doctor {$doctorId}: " . $e->getMessage());
                Log::error("Doctor Report Error ({$doctorId}): " . $e->getMessage());
            }
        }
        
        $this->info('Doctor reports sent.');
    }
}
