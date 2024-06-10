# Website Uptime & Certificate Monitoring API
[![run-tests](https://github.com/J-T-McC/uptime-backend-api/actions/workflows/run_tests.yml/badge.svg?branch=main)](https://github.com/J-T-McC/uptime-backend-api/actions/workflows/run_tests.yml)

This is a backend API to be paired with [Uptime VueJS](https://github.com/J-T-McC/uptime-frontend-vue) (or what ever else you want to use it for).

This repository implements: 

* [Laravel Fortify](https://laravel.com/docs/11.x/fortify)
* [Laravel Sanctum](https://laravel.com/docs/11.x/sanctum)
* [Spatie Uptime Monitor](https://github.com/spatie/laravel-uptime-monitor)

You can try it out at [https://uptime.tysonmccarney.com/](https://uptime.tysonmccarney.com/)

## Requirements
 
* PHP ^8.3
 
## Installation

```shell script

composer install

php artisan migrate

```

Some things to make note of while populating your .env:

1. Laravel Sanctum requires your SPA and backend to share the same domain. 
   Ex: app.example.com & api.example.com
1. Make sure your SESSION_DOMAIN value allows session cookies to be valid for both domains

```dotenv

APP_URL=https://api.my-domain.com
APP_SPA_URL=https://app.my-domain.com
SANCTUM_STATEFUL_DOMAINS=app.my-domain.com
SESSION_DOMAIN=.my-domain.com

```

To start the uptime and certificate checks, create a cron job to run the scheduler:

```shell script

php artisan schedule:run

```

## Notification Channels

Currently supports:
* Email
* Slack Webhooks
* Discord Webhooks

## License

MIT
