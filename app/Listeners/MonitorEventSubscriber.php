<?php

namespace App\Listeners;

use App\Models\Enums\Category;
use App\Models\Enums\CertificateStatus;
use App\Models\Enums\UptimeStatus;

use Spatie\UptimeMonitor\Events\UptimeCheckFailed;
use Spatie\UptimeMonitor\Events\UptimeCheckSucceeded;
use Spatie\UptimeMonitor\Events\UptimeCheckRecovered;

use Spatie\UptimeMonitor\Events\CertificateCheckFailed;
use Spatie\UptimeMonitor\Events\CertificateExpiresSoon;

class MonitorEventSubscriber
{


    /**
     * Handle Uptime Events
     *
     * @param object $event
     * @return void
     */
    public function handleUptimeEvent($event)
    {
        $event->monitor->monitorEvents()->create([
            'category' => Category::UPTIME,
            'status' => UptimeStatus::getStatusFromName($event->monitor->uptime_status),
            'error' => $event->monitor->uptime_check_failure_reason ?? null,
            'user_id' => $event->monitor->user_id,
        ]);
    }

    /**
     * Handle Certificate Events
     *
     * @param object $event
     * @return void
     */
    public function handleCertificateEvent($event)
    {

        $status = CertificateStatus::getStatusFromName($event->monitor->certificate_status);

        $error = $status === CertificateStatus::VALID ?
            "Certificate expires soon: {$event->monitor->certificate_expiration_date}" :
            $event->monitor->certificate_check_failure_reason ?? $event->monitor->uptime_check_failure_reason ?? null;

        $event->monitor->monitorEvents()->create([
            'category' => Category::CERTIFICATE,
            'status' => CertificateStatus::getStatusFromName($event->monitor->certificate_status),
            'error' => $error,
            'user_id' => $event->monitor->user_id
        ]);
    }

    public function subscribe($events)
    {
        $events->listen(
            [
                UptimeCheckFailed::class,
                UptimeCheckRecovered::class,
                UptimeCheckSucceeded::class,
            ],
            [self::class, 'handleUptimeEvent']
        );

        $events->listen(
            [
                CertificateCheckFailed::class,
                CertificateExpiresSoon::class,
            ],
            [self::class, 'handleCertificateEvent']
        );
    }
}
