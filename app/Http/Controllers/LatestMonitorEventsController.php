<?php

namespace App\Http\Controllers;

use App\Http\Resources\MonitorEventResource;
use App\Models\Enums\Category;
use App\Models\Enums\CertificateStatus;
use App\Models\Enums\UptimeStatus;
use App\Models\MonitorEvent;

class LatestMonitorEventsController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return MonitorEventResource::collection(
            MonitorEvent::with('monitor')
                ->where(fn($query) => self::applyStatusRequirements($query) )
                ->orderBy('created_at', 'desc')
                ->paginate(5)
        );
    }

    /**
     * Display a listing of the resource.
     * @param int $id
     */
    public function show(int $id)
    {
        return MonitorEventResource::collection(
            MonitorEvent::with('monitor')
                ->monitorFilter($id)
                ->where(fn($query) => self::applyStatusRequirements($query) )
                ->orderBy('created_at', 'desc')->paginate(5)
        );
    }

    private static function applyStatusRequirements(&$query) {
        $query->where(function ($query) {
            $query
                ->where('category', Category::UPTIME)
                ->whereIn('status', [
                    UptimeStatus::RECOVERED,
                    UptimeStatus::OFFLINE,
                ]);
        })->orWhere(function ($query) {
            $query
                ->where('category', Category::CERTIFICATE)
                ->whereIn('status', [
                    CertificateStatus::VALID,
                    CertificateStatus::INVALID,
                    CertificateStatus::EXPIRED,
                ]);
        });
    }

}
