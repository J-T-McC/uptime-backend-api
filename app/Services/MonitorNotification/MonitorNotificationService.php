<?php

namespace App\Services\MonitorNotification;

use App\Models\User;
use App\Models\Monitor;
use App\Models\Channel;
use Illuminate\Notifications\AnonymousNotifiable;
use App\Exceptions\UndefinedPropertyException;

/**
 * @property-read Monitor $monitor
 * @property-read User $owner
 * @property-read Channel $channels
 */

class MonitorNotificationService
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
           case 'owner':
               return $this->monitor->user;
           case 'channels':
               return $this->monitor->channels;
           default:
               throw new UndefinedPropertyException();
       }
    }

    private function dispatchNotifications() {
        $this->channels->each(function($channel) {
            //Dispatch independently to accommodate multiples of the same channel
            $notifiable = new AnonymousNotifiable();
            $notifiable = $notifiable->route($channel->type, $channel->endpoint);
            $notifiable->notify($this->notification);
        });
    }

}
