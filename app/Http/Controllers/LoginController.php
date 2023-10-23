<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        $inputs = $request->only('email', 'password');

        throw_if(
            ! Auth::attempt($inputs),
            new \Illuminate\Auth\Access\AuthorizationException()
        );

        $user = Auth::user();
        $token = $user->createToken('authToken')->plainTextToken;

        return api([
            'token_type' => 'Bearer',
            'access_token' => $token,
        ]);
    }
}
