<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use Spatie\UptimeMonitor\Notifications\Notifications\CertificateCheckSucceeded as SpatieCertificateCheckSucceeded;

class CertificateCheckSucceeded extends SpatieCertificateCheckSucceeded
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return config('uptime-monitor.notifications.integrated-services');
    }

//    /**
//     * Get the mail representation of the notification.
//     *
//     * @param  mixed  $notifiable
//     * @return \Illuminate\Notifications\Messages\MailMessage
//     */
//    public function toMail($notifiable)
//    {
//        return (new MailMessage)
//                    ->line('The introduction to the notification.')
//                    ->action('Notification Action', url('/'))
//                    ->line('Thank you for using our application!');
//    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
