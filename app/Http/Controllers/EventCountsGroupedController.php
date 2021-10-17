<?php

namespace App\Http\Controllers;

use App\Models\Monitor;
use App\Services\UptimeEventData;
use Illuminate\Http\JsonResponse;

class EventCountsGroupedController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json((new UptimeEventData())->past90Days());
    }

    /**
     * @param Monitor $monitor
     * @return JsonResponse
     */
    public function show(Monitor $monitor): JsonResponse
    {
        return response()->json((new UptimeEventData($monitor))->past90Days());
    }
}
