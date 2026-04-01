<?php

namespace App\Notifications;

use App\Models\Employee;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewEmployeeNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected Employee $employee
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Employee Added')
            ->greeting("Hi {$notifiable->name}!")
            ->line("A new employee has been added to your company.")
            ->line("**Name:** {$this->employee->first_name} {$this->employee->last_name}")
            ->line("**Email:** " . ($this->employee->email ?? 'N/A'))
            ->line("**Phone:** " . ($this->employee->phone ?? 'N/A'))
            ->line('Thank you for using our application!');
    }
}
