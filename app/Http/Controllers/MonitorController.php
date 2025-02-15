<?php

namespace App\Http\Controllers;

use App\Http\Queries\IndexMonitorsQuery;
use App\Http\Requests\StoreMonitorRequest;
use App\Http\Requests\UpdateMonitorRequest;
use App\Http\Resources\MonitorResource;
use App\Models\Monitor;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;

class MonitorController extends Controller
{
    /**
     * List the resource.
     *
     * @return AnonymousResourceCollection<LengthAwarePaginator<MonitorResource>>
     */
    public function index(IndexMonitorsQuery $query): AnonymousResourceCollection
    {
        return MonitorResource::collection(
            $query->get()
        );
    }

    /**
     * Create a resource.
     */
    public function store(StoreMonitorRequest $request): MonitorResource
    {
        /** @status 201 */
        return MonitorResource::make(
            $request->user()?->monitors()->create(
                $request->validated()
            )
        );
    }

    /**
     * Show a resource.
     */
    public function show(Monitor $monitor): MonitorResource
    {
        return MonitorResource::make($monitor);
    }

    /**
     * Update a resource.
     */
    public function update(UpdateMonitorRequest $request, Monitor $monitor): MonitorResource
    {
        $monitor->update($request->validated());

        return MonitorResource::make($monitor->refresh());
    }

    /**
     * Delete a resource.
     */
    public function destroy(Monitor $monitor): Response
    {
        $monitor->delete();

        return response()->noContent();
    }
}
