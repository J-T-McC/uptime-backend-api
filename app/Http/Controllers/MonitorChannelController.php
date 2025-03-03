<?php

namespace App\Http\Controllers;

use App\Models\Monitor;
use App\Services\HashId\HashId;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MonitorChannelController extends Controller
{
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Monitor $monitor): Response
    {
        $channelsToAttach = [];

        foreach ($request->all() as $input => $value) {
            if ($value) {
                $channelsToAttach[] = (new HashId)->decode($input);
            }
        }

        $monitor->channels()->sync(
            $request->user()?->channels()->whereIn('id', $channelsToAttach)->get() ?? []
        );

        return response()->noContent();
    }
}
