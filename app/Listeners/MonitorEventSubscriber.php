<?php

namespace App\Listeners;

use App\Events\IncrementUptimeCount;
use App\Models\Enums\Category;
use App\Models\Enums\CertificateStatus;
use App\Models\Enums\UptimeStatus;
use Spatie\UptimeMonitor\Events\UptimeCheckFailed;
use Spatie\UptimeMonitor\Events\UptimeCheckRecovered;
use Spatie\UptimeMonitor\Events\UptimeCheckSucceeded;
use Spatie\UptimeMonitor\Events\CertificateCheckFailed;
use Spatie\UptimeMonitor\Events\CertificateExpiresSoon;

class MonitorEventSubscriber
{
    /**
     * @param UptimeCheckRecovered $event
     * @return void
     */
    public function handleUptimeEventRecovered(UptimeCheckRecovered $event)
    {
        $event->monitor->uptime_status = 'recovered';
        /* @phpstan-ignore-next-line */
        $event->monitor->monitorEvents()->create([
            'category' => Category::UPTIME,
            'status' => UptimeStatus::getStatusFromName($event->monitor->uptime_status)->value,
            'error' => "Recovered after {$event->downtimePeriod->duration()}",
            'user_id' => $event->monitor->user_id,
        ]);

        $this->dispatchIncrementUptimeCountEvent($event);
    }


    /**
     * @param UptimeCheckFailed $event
     * @return void
     */
    public function handleUptimeEventFailed(UptimeCheckFailed $event)
    {
        /* @phpstan-ignore-next-line */
        $event->monitor->monitorEvents()->create([
            'category' => Category::UPTIME,
            'status' => UptimeStatus::getStatusFromName($event->monitor->uptime_status)->value,
            'error' => $event->monitor->uptime_check_failure_reason ?? null,
            'user_id' => $event->monitor->user_id,
        ]);

        $this->dispatchIncrementUptimeCountEvent($event);
    }

    /**
     * Handle Certificate Events
     *
     * @param object $event
     * @return void
     */
    public function handleCertificateEvent($event)
    {
        $status = CertificateStatus::getStatusFromName($event->monitor->certificate_status)->value;

        $error = $status === CertificateStatus::VALID ?
            "Certificate expires soon: {$event->monitor->certificate_expiration_date}" :
            $event->monitor->certificate_check_failure_reason ?? $event->monitor->uptime_check_failure_reason;

        $event->monitor->monitorEvents()->create([
            'category' => Category::CERTIFICATE,
            'status' => CertificateStatus::getStatusFromName($event->monitor->certificate_status)->value,
            'error' => $error,
            'user_id' => $event->monitor->user_id
        ]);
    }

    public function dispatchIncrementUptimeCountEvent($event)
    {
        event(new IncrementUptimeCount($event->monitor));
    }

    public function subscribe($events)
    {
        $events->listen(
            [UptimeCheckFailed::class],
            [self::class, 'handleUptimeEventFailed']
        );

        $events->listen(
            [UptimeCheckRecovered::class],
            [self::class, 'handleUptimeEventRecovered']
        );

        $events->listen(
            [UptimeCheckSucceeded::class],
            [self::class, 'dispatchIncrementUptimeCountEvent']
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
