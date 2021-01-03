<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\Enums\HttpResponse;
use App\Models\Monitor;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MonitorChannelController extends Controller
{

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id)
    {
        $attach = [];
        foreach($request->all() as $input => $value) {
            if($value) {
                $attach[] = (int)preg_replace('/[^0-9]/', '', $input);
            }
        }
        try {
            //attach channels to monitor if authenticated user owns them (Channel model scope)
            Monitor::findOrFail($id)->channels()->sync(Channel::whereIn('id', $attach)->pluck('id')->toArray());
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'URL Monitor not found'], HttpResponse::NOT_FOUND);
        }
        return response()->json(['message' => 'Channels enabled'], HttpResponse::SUCCESSFUL);
    }
}
