<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /*public function create()
    {
        return view('register.create');
    }*/

    public function register(RegisterRequest $request): \Illuminate\Http\JsonResponse
    {

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:7',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        
        return response()->json(['message' => 'Congratulations! Registration is successful.'], 201);
    }
}
