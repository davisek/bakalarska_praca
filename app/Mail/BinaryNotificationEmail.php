<?php

namespace App\Mail;

use App\Models\Measurement;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BinaryNotificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public Measurement $measurement;
    public User $user;

    public function __construct(Measurement $measurement, User $user)
    {
        $this->measurement = $measurement;
        $this->user = $user;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: trans('subjects.notification_subject'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.' . $this->user->locale->value . '.binary-notification-email',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
