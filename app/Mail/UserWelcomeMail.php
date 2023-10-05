<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public User $user)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'FControl - Confirmação de endereço de e-mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $urlVerifyEmail = config('app.frontend_url') . '/verify-email/' . $this->user->uuid;

        return new Content(
            markdown: 'emails.users.welcome',
            with: [
                'firstName' => explode(" ", $this->user->name)[0],
                'url' => $urlVerifyEmail,
                'confirmEmailUrl' => $urlVerifyEmail,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
