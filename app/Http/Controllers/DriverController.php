<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Enums\HttpResponse;

use App\Http\Requests\DriverRequest;
use App\Http\Resources\DriverResource;

use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return DriverResource
     */
    public function index()
    {
        return new DriverResource(Driver::simplePaginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param DriverRequest $request
     * @return DriverResource|JsonResponse
     */
    public function store(DriverRequest $request)
    {
        $driver = auth()->user()->drivers()->create($request->validated());
        return new DriverResource([$driver]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return DriverResource|JsonResponse
     */
    public function show(int $id)
    {
        try {
            return new DriverResource([Driver::findOrFail($id)]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Notification Driver not found'], HttpResponse::NOT_FOUND);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param DriverRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(DriverRequest $request, int $id)
    {
        try {
            Driver::findOrFail($id)->update($request->validated());
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Notification Driver not found'], HttpResponse::NOT_FOUND);
        }
        return response()->json(['message' => 'Notification Driver updated'], HttpResponse::SUCCESSFUL);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id)
    {
        try {
            Driver::findOrFail($id)->delete();
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Notification Driver not found'], HttpResponse::NOT_FOUND);
        }

        return response()->json(['message' => 'Notification Driver deleted'], HttpResponse::SUCCESSFUL);
    }
}
