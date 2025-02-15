# Website Uptime & Certificate Monitoring API
[![run-tests](https://github.com/J-T-McC/uptime-backend-api/actions/workflows/run_tests.yml/badge.svg?branch=main)](https://github.com/J-T-McC/uptime-backend-api/actions/workflows/run_tests.yml)

This is an uptime API to be paired with [Uptime VueJS](https://github.com/J-T-McC/uptime-frontend-vue) (or what ever else you want to use it for).

This is also a personal playground for trying out ecosystem tools and libraries.

You can try it out at [https://uptime.tysonmccarney.com/](https://uptime.tysonmccarney.com/)

## Requirements
 
* PHP ^8.3
 
## Local Installation (docker via sail)

```shell script

# create your environment file
cp -p .env.example .env;

# install composer dependencies (or run `composer install` if you have composer & PHP installed locally)
# see https://laravel.com/docs/11.x/sail#installing-composer-dependencies-for-existing-projects
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php84-composer:latest \
    composer install --ignore-platform-reqs

# build & start the docker container. ensure you add your test domain to your host file
./vendor/bin/sail build && ./vendor/bin/sail up -d;

# run the migrations
./vendor/bin/sail artisan migrate;

# optional test data (test@example.com / password)
./vendor/bin/sail artisan db:seed --class=LocalDatabaseSeeder;

# optional assign admin role to a user
# admin role grants access to api.my-domain.com/admin filament and laravel pulse dashboard
./vendor/bin/sail artisan admin:assign-role-to-user --user-id=1 --role-id=1;

```

Some things to make note of while populating your .env:

1. Laravel Sanctum requires your SPA and backend to share the same root domain. 
2. Make sure your SESSION_DOMAIN value allows cookies to be valid for both domains
3. Make sure your SANCTUM_STATEFUL_DOMAINS value is set to your SPA domain

```dotenv

APP_URL=https://api.my-domain.com
APP_SPA_URL=https://app.my-domain.com
SANCTUM_STATEFUL_DOMAINS=app.my-domain.com
SESSION_DOMAIN=.my-domain.com

```

Run the scheduler to check for uptime and expiring certificates:

```shell script

php artisan schedule:run

```

## Admin Panel and OpenAPI Documentation
1. Admin Panel: [http://api.laravel.test/admin](hhttp://api.laravel.test/admin)
2. OpenAPI Documentation: [http://api.laravel.test/docs/api](hhttp://api.laravel.test/docs/api)

## Notification Channels

Currently supports:
* Email
* Slack Webhooks
* Discord Webhooks
* PagerDuty v2 Events API

## License

MIT
