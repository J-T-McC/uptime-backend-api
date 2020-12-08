<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\Enums\HttpResponse;

use App\Http\Requests\ChannelRequest;
use App\Http\Resources\ChannelResource;

use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ChannelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return ChannelResource
     */
    public function index()
    {
        return new ChannelResource(Channel::simplePaginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ChannelRequest $request
     * @return ChannelResource|JsonResponse
     */
    public function store(ChannelRequest $request)
    {
        $channel = auth()->user()->channels()->create($request->validated());
        return new ChannelResource([$channel]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return ChannelResource|JsonResponse
     */
    public function show(int $id)
    {
        try {
            return new ChannelResource([Channel::findOrFail($id)]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Notification Channel not found'], HttpResponse::NOT_FOUND);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param ChannelRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(ChannelRequest $request, int $id)
    {
        try {
            Channel::findOrFail($id)->update($request->validated());
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Notification Channel not found'], HttpResponse::NOT_FOUND);
        }
        return response()->json(['message' => 'Notification Channel updated'], HttpResponse::SUCCESSFUL);
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
            Channel::findOrFail($id)->delete();
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Notification Channel not found'], HttpResponse::NOT_FOUND);
        }

        return response()->json(['message' => 'Notification Channel deleted'], HttpResponse::SUCCESSFUL);
    }
}
