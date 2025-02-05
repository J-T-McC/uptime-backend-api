<?php

namespace App\Http\Controllers;

use App\Models\Monitor;
use App\Services\UptimeEventData;
use Illuminate\Http\JsonResponse;

class EventCountsTrendedController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return response()->json((new UptimeEventData)->trended());
    }

    /**
     * Display a listing of the resource.
     */
    public function show(Monitor $monitor): JsonResponse
    {
        return response()->json((new UptimeEventData($monitor))->trended());
    }
}
