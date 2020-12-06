<?php

namespace App\Services\MonitorNotification;

use App\Models\User;
use App\Models\Monitor;
use App\Models\MonitorDriver;
use Illuminate\Notifications\AnonymousNotifiable;
use App\Services\MonitorNotification\Exceptions\UndefinedPropertyException;

/**
 * @property-read Monitor $monitor
 * @property-read User $owner
 * @property-read MonitorDriver $drivers
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
           case 'drivers':
               return $this->monitor->drivers;
           default:
               throw new UndefinedPropertyException();
       }
    }

    private function dispatchNotifications() {
        $this->drivers->each(function($driver) {
            //Dispatch independently to accommodate multiples of the same driver
            $notifiable = new AnonymousNotifiable();
            $notifiable->route($driver->type, $driver->endpoint);
            $notifiable->notify($this->notification);
        });
    }

}
