<?php

namespace App\Notifications;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TransactionFailed extends Notification implements ShouldQueue
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
            ->error()
            ->subject('Transaction Failed - Modremit')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('The remittance transaction has failed.')
            ->line('Amount: ' . $this->transaction->chf_amount . ' CHF')
            ->line('Recipient: ' . $this->transaction->recipient->name)
            ->line('Reason: ' . ($this->transaction->notes ?? 'Unknown error'))
            ->action('View Transaction', url('/agent/transactions/' . $this->transaction->id))
            ->line('Please check the details and try again.');
    }
}
