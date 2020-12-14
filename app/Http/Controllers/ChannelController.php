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
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return ChannelResource::collection(Channel::orderBy('type')->get());
    }

    /**
     * Store a newly created resource in storage.
     * @param ChannelRequest $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function store(ChannelRequest $request)
    {
        $channel = auth()->user()->channels()->create($request->validated());
        return ChannelResource::collection([$channel]);
    }

    /**
     * Display the specified resource.
     * @param int $id
     * @return JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function show(int $id)
    {
        try {
            return ChannelResource::collection([Channel::findOrFail($id)]);
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
