<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WithdrawalRequest extends Mailable
{
    use Queueable, SerializesModels;

    public int $id;
    public string $name;
    public string $email;
    public float $amount;

    /**
     * Create a new message instance.
     */
    public function __construct(int $id, string $name, string $email, float $amount)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->amount = $amount;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address("support@dreamforlyfintl.com", "DreamForLyf International"),
            subject: 'Withdrawal Request',
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
            view: 'email.withdrawal-request',
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
