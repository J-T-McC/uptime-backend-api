<?php

namespace App\Http\Controllers;

use App\Models\Enums\HttpResponse;
use Illuminate\Http\Request;
use App\Http\Requests\TokenAuthRequest;
use Illuminate\Support\Facades\Auth;

use App\Models\User;

class TokenAuthController extends Controller
{

    /**
     * @param TokenAuthRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(TokenAuthRequest $request) : \Illuminate\Http\JsonResponse
    {
        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Username or password is incorrect'
            ], HttpResponse::UNAUTHORISED);
        }

        return response()->json([
            'token' => Auth::user()->createToken('auth-token')->plainTextToken,
            'type' => 'Bearer',
        ], HttpResponse::SUCCESSFUL);

    }
}
