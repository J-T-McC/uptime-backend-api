<?php

namespace App\Notifications;

class AnonymousNotifiable extends \Illuminate\Notifications\AnonymousNotifiable
{
    public function routeNotificationFor($driver)
    {
        // Laravel has migrated to using Slack bot apps for default Slack notifications.
        // Laravel still supports Slack webhook notifications but this results a type error in some cases.
        // - SlackNotificationRouterChannel::send expects this method to return false vs null
        // - If null is returned, laravel attempts to dispatch a new "bot" Slack notification causing a type error
        return $this->routes[$driver] ?? false;
    }
}
