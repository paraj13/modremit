<?php

namespace App\Notifications;

use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class KycStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Customer $customer, public string $status)
    {}

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $statusLabel = ucfirst($this->status);
        $color = $this->status === 'approved' ? 'success' : 'error';

        $message = (new MailMessage)
            ->subject('KYC Status Updated: ' . $statusLabel)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('The KYC status for customer ' . $this->customer->name . ' has been updated to: ' . $statusLabel);

        if ($this->status === 'approved') {
            $message->line('This customer can now proceed with transactions.');
        } else {
            $message->line('Please review the customer details.');
        }

        return $message->action('View Customer', url('/agent/customers/' . $this->customer->id))
            ->line('Modremit Compliance Team');
    }
}
