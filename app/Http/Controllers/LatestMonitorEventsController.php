<?php

namespace App\Http\Controllers;

use App\Http\Resources\MonitorEventResource;
use App\Models\Monitor;
use App\Models\MonitorEvent;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class LatestMonitorEventsController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return MonitorEventResource::collection(
            MonitorEvent::query()
                ->applyStatusRequirements()
                ->with('monitor')
                ->latest()
                ->paginate(5)
        );
    }

    /**
     * Display a listing of the resource.
     */
    public function show(Monitor $monitor): AnonymousResourceCollection
    {
        return MonitorEventResource::collection(
            MonitorEvent::query()
                ->applyStatusRequirements()
                ->with('monitor')
                ->monitorFilter($monitor)
                ->latest()
                ->paginate(5)
        );
    }
}
