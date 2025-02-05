<?php

namespace App\Listeners;

use App\Enums\Category;
use App\Enums\CertificateStatus;
use App\Enums\UptimeStatus;
use App\Events\IncrementUptimeCount;
use Spatie\UptimeMonitor\Events\CertificateCheckFailed;
use Spatie\UptimeMonitor\Events\CertificateExpiresSoon;
use Spatie\UptimeMonitor\Events\UptimeCheckFailed;
use Spatie\UptimeMonitor\Events\UptimeCheckRecovered;
use Spatie\UptimeMonitor\Events\UptimeCheckSucceeded;

class MonitorEventSubscriber
{
    public function handleUptimeEventRecovered(UptimeCheckRecovered $event): void
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

    public function handleUptimeEventFailed(UptimeCheckFailed $event): void
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

    public function handleCertificateEvent(CertificateCheckFailed|CertificateExpiresSoon $event): void
    {
        $status = CertificateStatus::getStatusFromName($event->monitor->certificate_status)->value;

        $error = $status === CertificateStatus::VALID->value ?
            "Certificate expires soon: {$event->monitor->certificate_expiration_date}" :
            $event->monitor->certificate_check_failure_reason ?? $event->monitor->uptime_check_failure_reason;

        /* @phpstan-ignore-next-line */
        $event->monitor->monitorEvents()->create([
            'category' => Category::CERTIFICATE,
            'status' => CertificateStatus::getStatusFromName($event->monitor->certificate_status)->value,
            'error' => $error,
            'user_id' => $event->monitor->user_id,
        ]);
    }

    private function dispatchIncrementUptimeCountEvent(UptimeCheckSucceeded|UptimeCheckFailed|UptimeCheckRecovered $event): void
    {
        event(new IncrementUptimeCount($event->monitor));
    }

    public function handleUptimeCheckSucceeded(UptimeCheckSucceeded $event): void
    {
        $this->dispatchIncrementUptimeCountEvent($event);
    }
}
