<?php

namespace App\Mail;

use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class DailyAdminReport extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public Collection $appointments, public Carbon $date)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reporte Diario de Citas Administrativo - ' . $this->date->format('d/m/Y'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.reports.admin',
            with: [
                'appointments' => $this->appointments,
                'date' => $this->date
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $pdf = Pdf::loadView('pdfs.admin_daily_report', [
             'appointments' => $this->appointments,
             'date' => $this->date
        ]);
         
        return [
             Attachment::fromData(fn () => $pdf->output(), 'Reporte-Admin-'.$this->date->format('Y-m-d').'.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
