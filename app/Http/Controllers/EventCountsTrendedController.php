<?php

namespace App\Http\Controllers;

use App\Models\Monitor;
use App\Services\UptimeEventData;
use Illuminate\Http\JsonResponse;

class EventCountsTrendedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json((new UptimeEventData())->trended());
    }

    /**
     * Display a listing of the resource.
     *
     * @param Monitor $monitor
     * @return JsonResponse
     */
    public function show(Monitor $monitor): JsonResponse
    {
        return response()->json((new UptimeEventData($monitor))->trended());
    }
}
