<?php

namespace App\Http\Controllers;

use App\Http\Requests\MonitorRequest;
use App\Http\Resources\MonitorResource;
use App\Models\Enums\HttpResponse;
use Illuminate\Http\JsonResponse;

use App\Models\Monitor;
use Spatie\UptimeMonitor\Exceptions\CannotSaveMonitor;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MonitorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return MonitorResource
     */
    public function index()
    {
        return new MonitorResource(Monitor::simplePaginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\MonitorRequest $request
     * @return MonitorResource|JsonResponse
     */
    public function store(MonitorRequest $request)
    {
        try {
            $monitor = auth()->user()->monitors()->create($request->validated());
        } catch (CannotSaveMonitor $e) {
            return response()->json([
                'message' => 'URL Monitor already exists',
            ], HttpResponse::CONFLICT);
        }

        return new MonitorResource([$monitor]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return MonitorResource|JsonResponse
     */
    public function show(int $id)
    {
        try {
            return new MonitorResource([Monitor::findOrFail($id)]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'URL Monitor not found'], HttpResponse::NOT_FOUND);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param MonitorRequest $request
     * @param int $id
     */
    public function update(MonitorRequest $request, int $id)
    {
        try {
           Monitor::findOrFail($id)->update($request->validated());
        } catch (CannotSaveMonitor $e) {
            return response()->json([
                'message' => 'URL Monitor already exists',
            ], HttpResponse::CONFLICT);
        }
        return response()->json(['message' => 'URL Monitor updated'], HttpResponse::SUCCESSFUL);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     */
    public function destroy(int $id)
    {
        try {
            Monitor::findOrFail($id)->delete();
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'URL Monitor not found'], HttpResponse::NOT_FOUND);
        }

        return response()->json(['message' => 'URL Monitor deleted'], HttpResponse::SUCCESSFUL);
    }
}
