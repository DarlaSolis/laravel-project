<?php

namespace App\Notifications;

use App\Jobs\SendWhatsAppNotificationJob;
use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class AppointmentReminderNotification extends Notification implements ShouldQueue
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
        return [];
    }

    /**
     * Custom method to dispatch the reminder WhatsApp job.
     */
    public function toWhatsApp(object $notifiable)
    {
        if (empty($notifiable->phone)) {
            \Log::warning("Cannot send WhatsApp reminder to User ID {$notifiable->id}. No phone number found.");
            return;
        }

        $patientName = $notifiable->name;
        $appointmentDate = \Carbon\Carbon::parse($this->appointment->date)->format('d/m/Y');
        $appointmentTime = \Carbon\Carbon::parse($this->appointment->start_time)->format('H:i');

        $message = "Hola {$patientName}, te recordamos que tienes una cita médica mañana.\n\n" .
                   "Fecha: {$appointmentDate}\n" .
                   "Hora: {$appointmentTime}\n\n" .
                   "Te esperamos en la clínica.";

        SendWhatsAppNotificationJob::dispatch($notifiable->phone, $message);
    }
}
