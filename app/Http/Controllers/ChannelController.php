<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChannelRequest;
use App\Http\Requests\UpdateChannelRequest;
use App\Http\Resources\ChannelResource;
use App\Models\Channel;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class ChannelController extends Controller
{
    /**
     * List the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        return ChannelResource::collection(
            Channel::query()->orderBy('type')->get()
        );
    }

    /**
     * Create a resource.
     */
    public function store(StoreChannelRequest $request): ChannelResource
    {
        return ChannelResource::make(
            $request->user()?->channels()->create(
                $request->validated()
            )
        );
    }

    /**
     * Show a resource.
     */
    public function show(Channel $channel): ChannelResource
    {
        return ChannelResource::make($channel);
    }

    /**
     * Update a resource.
     */
    public function update(UpdateChannelRequest $request, Channel $channel): ChannelResource
    {
        $channel->update($request->validated());

        return ChannelResource::make($channel->refresh());
    }

    /**
     * Delete a resource.
     */
    public function destroy(Channel $channel): Response
    {
        $channel->delete();

        return response()->noContent();
    }
}
