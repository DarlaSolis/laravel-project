<?php

namespace App\Notifications;

use App\Jobs\SendWhatsAppNotificationJob;
use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class AppointmentConfirmationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $appointment;

    /**
     * Create a new notification instance.
     */
    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // We will dispatch our custom job instead of using a built-in channel
        return [];
    }

    /**
     * Custom method to send the WhatsApp notification manually
     * since we are using a custom Job.
     */
    public function toWhatsApp(object $notifiable)
    {
        // The notifiable object should be the User (Patient)
        // Ensure the user has a phone number
        if (empty($notifiable->phone)) {
            \Log::warning("Cannot send WhatsApp confirmation to User ID {$notifiable->id}. No phone number found.");
            return;
        }

        $patientName = $notifiable->name;
        $appointmentDate = \Carbon\Carbon::parse($this->appointment->date)->format('d/m/Y');
        $appointmentTime = \Carbon\Carbon::parse($this->appointment->start_time)->format('H:i');
        
        // Eager load doctor and doctor's user relation if not loaded
        $this->appointment->loadMissing('doctor.user');
        $doctorName = $this->appointment->doctor->user->name ?? 'Doctor General';

        $message = "Hola {$patientName}, tu cita médica ha sido confirmada.\n\n" .
                   "Fecha: {$appointmentDate}\n" .
                   "Hora: {$appointmentTime}\n" .
                   "Doctor: {$doctorName}\n\n" .
                   "Gracias por confiar en nuestra clínica.";

        // Dispatch the job to the queue
        SendWhatsAppNotificationJob::dispatch($notifiable->phone, $message);
    }
}
