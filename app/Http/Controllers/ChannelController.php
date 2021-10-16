<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChannelRequest;
use App\Http\Requests\UpdateChannelRequest;
use App\Models\Channel;
use App\Http\Requests\ChannelRequest;
use App\Http\Resources\ChannelResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class ChannelController extends Controller
{
    /**
     * List the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return ChannelResource::collection(
            Channel::query()->orderBy('type')->get()
        );
    }

    /**
     * Create a resource.
     *
     * @param StoreChannelRequest $request
     * @return ChannelResource
     */
    public function store(StoreChannelRequest $request): ChannelResource
    {
        return ChannelResource::make(
            auth()->user()->channels()->create(
                $request->validated()
            )
        );
    }

    /**
     * Show a resource.
     *
     * @param Channel $channel
     * @return ChannelResource
     */
    public function show(Channel $channel): ChannelResource
    {
        return ChannelResource::make($channel);
    }


    /**
     * Update a resource.
     *
     * @param UpdateChannelRequest $request
     * @param Channel $channel
     * @return ChannelResource
     */
    public function update(UpdateChannelRequest $request, Channel $channel): ChannelResource
    {
        $channel->update($request->validated());

        return ChannelResource::make($channel->refresh());
    }

    /**
     * Delete a resource.
     *
     * @param Channel $channel
     * @return Response
     */
    public function destroy(Channel $channel): Response
    {
        $channel->delete();

        return response()->noContent();
    }
}
