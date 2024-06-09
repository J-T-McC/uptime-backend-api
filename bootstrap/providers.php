<?php

use App\Providers\AppServiceProvider;
use App\Providers\FortifyServiceProvider;
use App\Services\UptimeMonitor\UptimeMonitorServiceProvider;

return [
    AppServiceProvider::class,
    FortifyServiceProvider::class,
    UptimeMonitorServiceProvider::class,
];
