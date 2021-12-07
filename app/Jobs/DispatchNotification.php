<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\UptimeMonitor\Notifications\Notifiable;

class DispatchNotification implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(private Notifiable|AnonymousNotifiable $notifiable, private Notification $notification)
    {
    }

    public function handle(): void
    {
        $this->notifiable->notify($this->notification);
    }
}
