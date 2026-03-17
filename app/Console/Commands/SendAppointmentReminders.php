<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Notifications\AppointmentReminderNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendAppointmentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointments:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends WhatsApp reminders for appointments scheduled for exactly 24 hours from now.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Target date: exactly 24 hours from now (1 day in advance)
        $tomorrow = Carbon::now()->addDay()->format('Y-m-d');
        
        $this->info("Looking for appointments on: {$tomorrow}");

        // Retrieve appointments for tomorrow
        // Assuming status 1 = Scheduled/Pendiente
        $appointments = Appointment::with('patient.user')
            ->whereDate('date', $tomorrow)
            ->where('status', 1) 
            ->get();

        if ($appointments->isEmpty()) {
            $this->info('No appointments found for tomorrow.');
            return;
        }

        $count = 0;

        foreach ($appointments as $appointment) {
            if ($appointment->patient && $appointment->patient->user) {
                $user = $appointment->patient->user;
                
                // Dispatch the custom reminder notification
                $notification = new AppointmentReminderNotification($appointment);
                $notification->toWhatsApp($user);
                
                $count++;
            } else {
                Log::warning("Skipped reminder for Appointment ID {$appointment->id} as no valid patient/user was found.");
            }
        }

        $this->info("Successfully dispatched {$count} WhatsApp reminders.");
    }
}
