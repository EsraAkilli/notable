<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeUserPasswordRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function me(): JsonResponse
    {
        return api(
            UserResource::make(request()->user())
        );
    }

    public function update(UpdateUserRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $update = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
        ];

        if ($request->filled('password')) {
            $update['password'] = $request->input('password');
        }

        $user->update($update);

        return api(
            UserResource::make(request()->user())
        );
    }

    public function changeUserPassword(ChangeUserPasswordRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $user->update([
            'password' =>$request->input('password'),
        ]);

        return apiSuccess();
    }
}
