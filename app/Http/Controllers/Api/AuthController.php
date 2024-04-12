<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Helpers\ApiResponse;

class AuthController extends Controller
{
    //
    public function login(Request $request)
    {

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $tokenResult = $user->createToken('auth_api');
            $token = $tokenResult->token;
            $token->expires_at = Carbon::now()->addMinutes(60);
            $accessToken = $tokenResult->accessToken;
            $expires = $tokenResult->token->expires_at;

            return ApiResponse::success([
                'access_token' => $accessToken,
                'user' => $user
            ], 'Login success', 200);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function logout()
    {
        $user = Auth::user();
        $user->token()->revoke();
        return ApiResponse::success(null, 'Logout success', 200);
    }
}
