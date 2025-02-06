<?php

return [

    /*
     * You can get notified when specific events occur. Out of the box you can use 'mail'
     * and 'slack'. Of course you can also specify your own notification classes.
     */
    'notifications' => [
        'integrated-services' => [
            'mail',
            'slack',
            \App\Notifications\Channels\Discord\DiscordChannel::class,
            \NotificationChannels\PagerDuty\PagerDutyChannel::class,
        ],

        /*
         * Additional endpoint validation requirements for creating and updating channel records by type
         */
        'service-endpoint-rules' => [
            'mail' => '|email',
            'slack' => '|url|active_url',
            'discord' => '|url|active_url',
            'PagerDuty' => '|string|size:32',
        ],

        'notifications' => [
            // values are arbitrary but required for the spatie library to track the event
            \App\Notifications\UptimeCheckFailed::class => ['enabled'],
            \App\Notifications\UptimeCheckRecovered::class => ['enabled'],
            \App\Notifications\UptimeCheckSucceeded::class => [],

            \App\Notifications\CertificateCheckFailed::class => ['enabled'],
            \App\Notifications\CertificateExpiresSoon::class => ['enabled'],
            \App\Notifications\CertificateCheckSucceeded::class => [],
        ],

        /*
         * The location from where you are running this Laravel application. This location will be
         * mentioned in all notifications that will be sent.
         */
        //        'location' => '',

        /*
         * To keep reminding you that a site is down, notifications
         * will be resent every given number of minutes.
         */
        'resend_uptime_check_failed_notification_every_minutes' => 30,

        /*
         * The date format used in notifications.
         */
        'date_format' => 'Y-m-d',
    ],

    'uptime_check' => [

        /*
         * When the uptime check could reach the url of a monitor it will pass the response to this class
         * If this class determines the response is valid, the uptime check will be regarded as succeeded.
         *
         * You can use any implementation of Spatie\UptimeMonitor\Helpers\UptimeResponseCheckers\UptimeResponseChecker here.
         */
        'response_checker' => Spatie\UptimeMonitor\Helpers\UptimeResponseCheckers\LookForStringChecker::class,

        /*
         * An uptime check will be performed if the last check was performed more than the
         * given number of minutes ago. If you change this setting you have to manually
         * update the `uptime_check_interval_in_minutes` value of your existing monitors.
         *
         * When an uptime check fails we'll check the uptime for that monitor every time `monitor:check-uptime`
         * runs regardless of this setting.
         */
        'run_interval_in_minutes' => 1,

        /*
         * To speed up the uptime checking process the package can perform the uptime check of several
         * monitors concurrently. Set this to a lower value if you're getting weird errors
         * running the uptime check.
         */
        'concurrent_checks' => 15,

        /*
         * The uptime check for a monitor will fail if the url does not respond after the
         * given number of seconds.
         */
        'timeout_per_site' => 30,

        /*
         * Because networks can be a bit unreliable the package can make three attempts
         * to connect to a server in one uptime check. You can specify the time in
         * milliseconds between each attempt.
         */
        'retry_connection_after_milliseconds' => 500,

        /*
         * If you want to change the default Guzzle client behaviour, you can do so by
         * passing custom options that will be used when making requests.
         */
        'guzzle_options' => [
            // 'allow_redirects' => false,
        ],

        /*
         * Fire `Spatie\UptimeMonitor\Events\MonitorFailed` event only after
         * the given number of uptime checks have consecutively failed for a monitor.
         */
        'fire_monitor_failed_event_after_consecutive_failures' => 1,

        /*
         * When reaching out to sites this user agent will be used.
         */
        'user_agent' => 'spatie/laravel-uptime-monitor uptime checker',

        /*
         * When reaching out to the sites these headers will be added.
         */
        'additional_headers' => [],
    ],

    'certificate_check' => [

        /*
         * The `Spatie\UptimeMonitor\Events\SslExpiresSoon` event will fire
         * when a certificate is found whose expiration date is in
         * the next number of given days.
         */
        'fire_expiring_soon_event_if_certificate_expires_within_days' => 15,
    ],

    /*
     * To add or modify behaviour to the Monitor model you can specify your
     * own model here. The only requirement is that it should extend
     * `Spatie\UptimeMonitor\Models\Monitor`.
     */
    'monitor_model' => App\Models\Monitor::class,
];
