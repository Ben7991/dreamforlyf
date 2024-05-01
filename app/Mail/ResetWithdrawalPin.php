<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetWithdrawalPin extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $email;
    public $pin;

    /**
     * Create a new message instance.
     */
    public function __construct(string $name, string $email, string $pin)
    {
        $this->name = $name;
        $this->email = $email;
        $this->pin = $pin;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address("support@dreamforlyfintl.com", "DreamForLyf International"),
            subject: 'Reset Withdrawal Pin',
            replyTo: [
                new Address($this->email, $this->name)
            ]
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.pin-reset',
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
