<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerifyAgentKycMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public \App\Models\User $agent,
        public string $verificationLink
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Verify Your Agent Identity - ' . config('app.name'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Reusing the same view as customer KYC, but passing $customer variable name for compatibility or updating view to use generic $user
        return new Content(
            view: 'emails.verify-kyc',
            with: [
                'customer' => $this->agent,
                'verificationLink' => $this->verificationLink,
            ],
        );
    }
}
