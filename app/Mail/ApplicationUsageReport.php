<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ApplicationUsageReport extends Mailable
{
    private string $reportFilepath;

    /**
     * Create a new message instance.
     */
    public function __construct(string $reportFilepath)
    {
        $this->reportFilepath = $reportFilepath;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Отчёт об использовании приложения',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.application_usage_report',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromPath($this->reportFilepath)
                ->withMime("application/json")
                ->as("Отчёт об использовании приложения.json")
        ];
    }
}
