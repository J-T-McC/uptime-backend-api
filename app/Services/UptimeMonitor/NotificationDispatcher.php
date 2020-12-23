<?php

namespace App\Services\UptimeMonitor;

use App\Jobs\DispatchNotification;
use App\Models\Monitor;
use App\Models\Channel;
use Illuminate\Notifications\AnonymousNotifiable;
use App\Exceptions\UndefinedPropertyException;

/**
 * @property-read Monitor $monitor
 * @property-read Channel $channels
 */
class NotificationDispatcher
{

    private $notification;

    public function __construct($notification)
    {
        $this->notification = $notification;
        $this->dispatchNotifications();
    }

    /**
     * @param $name
     * @return mixed
     * @throws UndefinedPropertyException
     */
    public function __get($name)
    {
       switch($name) {
           case 'monitor':
               return $this->notification->event->monitor;
           case 'channels':
               return $this->monitor->channels;
           default:
               throw new UndefinedPropertyException();
       }
    }

    private function dispatchNotifications() {
        //Dispatch independently to accommodate multiples of the same channel
        $this->channels->each(function($channel)  {
            $notifiable = new AnonymousNotifiable();
            $notifiable->route($channel->type, $channel->endpoint);
            DispatchNotification::dispatch($notifiable, $this->notification);
        });
    }

}
