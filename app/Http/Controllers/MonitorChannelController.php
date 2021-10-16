<?php

namespace App\Http\Controllers;

use App\Models\Monitor;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MonitorChannelController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Monitor $monitor
     * @return Response
     */
    public function update(Request $request, Monitor $monitor): Response
    {
        $channelsToAttach = [];
        foreach ($request->all() as $input => $value) {
            if ($value) {
                $channelsToAttach[] = (int)preg_replace('/[^0-9]/', '', $input);
            }
        }

        $monitor->channels()->sync(
            auth()->user()->channels()->whereIn('id', $channelsToAttach)->get()
        );

        return response()->noContent();
    }
}
