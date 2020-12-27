<?php

namespace App\Services\UptimeMonitor;

use Illuminate\Support\ServiceProvider;
use Spatie\UptimeMonitor\Commands\CheckCertificates;
use Spatie\UptimeMonitor\Commands\CheckUptime;
use Spatie\UptimeMonitor\Commands\CreateMonitor;
use Spatie\UptimeMonitor\Commands\DeleteMonitor;
use Spatie\UptimeMonitor\Commands\DisableMonitor;
use Spatie\UptimeMonitor\Commands\EnableMonitor;
use Spatie\UptimeMonitor\Commands\ListMonitors;
use Spatie\UptimeMonitor\Commands\SyncFile;
use Spatie\UptimeMonitor\Helpers\UptimeResponseCheckers\UptimeResponseChecker;

class UptimeMonitorServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app['events']->subscribe(EventHandler::class);

        $this->app->bind('command.monitor:check-uptime', CheckUptime::class);
        $this->app->bind('command.monitor:check-certificate', CheckCertificates::class);
        $this->app->bind('command.monitor:sync-file', SyncFile::class);
        $this->app->bind('command.monitor:create', CreateMonitor::class);
        $this->app->bind('command.monitor:delete', DeleteMonitor::class);
        $this->app->bind('command.monitor:enable', EnableMonitor::class);
        $this->app->bind('command.monitor:disable', DisableMonitor::class);
        $this->app->bind('command.monitor:list', ListMonitors::class);

        $this->app->bind(UptimeResponseChecker::class, config('uptime-monitor.uptime_check.response_checker'));

        $this->commands([
            'command.monitor:check-uptime',
            'command.monitor:check-certificate',
            'command.monitor:sync-file',
            'command.monitor:create',
            'command.monitor:delete',
            'command.monitor:enable',
            'command.monitor:disable',
            'command.monitor:list',
        ]);
    }
}
