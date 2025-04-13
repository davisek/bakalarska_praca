<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordResetEmail extends Mailable
{
    use Queueable, SerializesModels;

    public string $code;
    public string $fullName;

    public function __construct(string $code, string $fullName)
    {
        $this->code = $code;
        $this->fullName = $fullName;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: trans('subjects.password_reset_subject'),
        );
    }

    public function content(): Content
    {
        $locale = app()->getLocale();

        return new Content(
            view: 'emails.' . $locale . '.password-reset-email',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
