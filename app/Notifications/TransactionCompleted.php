<?php

namespace App\Notifications;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TransactionCompleted extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Transaction $transaction)
    {}

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Transaction Completed - Modremit')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('The remittance transaction has been successfully completed.')
            ->line('Amount Sent: ' . $this->transaction->chf_amount . ' CHF')
            ->line('Amount Received: ' . $this->transaction->target_amount . ' ' . $this->transaction->target_currency)
            ->line('Recipient: ' . $this->transaction->recipient->name)
            ->line('Payment Reference: ' . $this->transaction->payment_ref)
            ->action('View Transaction', url('/agent/transactions/' . $this->transaction->id))
            ->line('Thank you for using Modremit!');
    }
}
