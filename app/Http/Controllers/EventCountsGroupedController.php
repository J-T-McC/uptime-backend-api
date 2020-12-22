<?php

namespace App\Http\Controllers;

use App\Services\UptimeEventData;

class EventCountsGroupedController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json((new UptimeEventData())->past90Days());
    }

    /**
     * Display a listing of the resource.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        return response()->json((new UptimeEventData($id))->past90Days());
    }
}
