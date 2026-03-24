<?php

namespace App\Observers;

use App\Models\Appointment;
use App\Notifications\AppointmentConfirmationNotification;

class AppointmentObserver
{
    /**
     * Handle the Appointment "created" event.
     */
    public function created(Appointment $appointment): void
    {
        // When an appointment is created, we want to send the confirmation WhatsApp

        // Eager load the required relationships to avoid N+1 issues and errors
        $appointment->loadMissing('patient.user');

        // Check if the patient and their associated user account exist
        if ($appointment->patient && $appointment->patient->user) {
            
            // We dispatch the notification to the user associated with the patient
            $user = $appointment->patient->user;
            
            // Send the notification manually using our custom toWhatsApp method
            $notification = new AppointmentConfirmationNotification($appointment);
            $notification->toWhatsApp($user);
            
            \Log::info("Appointment confirmation workflow triggered for Appointment ID: {$appointment->id}");
            
            // Send Email PDF Receipt
            try {
                $patientEmail = $user->email ?? null;
                $doctorEmail = $appointment->doctor->user->email ?? null;
                
                $emails = array_filter([$patientEmail, $doctorEmail]);
                
                if (!empty($emails)) {
                    \Illuminate\Support\Facades\Mail::to($emails)->send(new \App\Mail\AppointmentReceipt($appointment));
                    \Log::info("Appointment receipt email sent for Appointment ID: {$appointment->id}");
                }
            } catch (\Exception $e) {
                \Log::error("Failed to send appointment receipt for ID {$appointment->id}: " . $e->getMessage());
            }
        } else {
            \Log::warning("Could not dispatch confirmation for Appointment ID {$appointment->id} because no patient/user is associated.");
        }
    }

    /**
     * Handle the Appointment "updated" event.
     */
    public function updated(Appointment $appointment): void
    {
        //
    }

    /**
     * Handle the Appointment "deleted" event.
     */
    public function deleted(Appointment $appointment): void
    {
        //
    }

    /**
     * Handle the Appointment "restored" event.
     */
    public function restored(Appointment $appointment): void
    {
        //
    }

    /**
     * Handle the Appointment "force deleted" event.
     */
    public function forceDeleted(Appointment $appointment): void
    {
        //
    }
}
