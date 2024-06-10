<?php

namespace App\Http\Controllers;

use App\Http\Queries\IndexMonitorsQuery;
use App\Http\Requests\StoreMonitorRequest;
use App\Http\Requests\UpdateMonitorRequest;
use App\Models\Monitor;
use App\Http\Resources\MonitorResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class MonitorController extends Controller
{
    /**
     * List the resource.
     *
     * @param IndexMonitorsQuery $query
     * @return AnonymousResourceCollection
     */
    public function index(IndexMonitorsQuery $query): AnonymousResourceCollection
    {
        return MonitorResource::collection(
            $query->get()
        );
    }

    /**
     * Create a resource.
     *
     * @param StoreMonitorRequest $request
     * @return MonitorResource
     */
    public function store(StoreMonitorRequest $request): MonitorResource
    {
        return MonitorResource::make(
            $request->user()?->monitors()->create(
                $request->validated()
            )
        );
    }

    /**
     * Show a resource.
     *
     * @param Monitor $monitor
     * @return MonitorResource
     */
    public function show(Monitor $monitor): MonitorResource
    {
        return MonitorResource::make($monitor);
    }


    /**
     * Update a resource.
     *
     * @param UpdateMonitorRequest $request
     * @param Monitor $monitor
     * @return MonitorResource
     */
    public function update(UpdateMonitorRequest $request, Monitor $monitor): MonitorResource
    {
        $monitor->update($request->validated());

        return MonitorResource::make($monitor->refresh());
    }

    /**
     * Delete a resource.
     *
     * @param Monitor $monitor
     * @return Response
     */
    public function destroy(Monitor $monitor): Response
    {
        $monitor->delete();

        return response()->noContent();
    }
}
