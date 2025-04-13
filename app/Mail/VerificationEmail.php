<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public string $verificationCode;
    public string $fullName;

    public function __construct(string $verificationCode, string $fullName)
    {
        $this->verificationCode = $verificationCode;
        $this->fullName = $fullName;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: trans('subjects.verification_subject'),
        );
    }

    public function content(): Content
    {
        $locale = app()->getLocale();

        return new Content(
            view: 'emails.' . $locale . '.verification-email',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
