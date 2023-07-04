<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $remember = $validated['remember'] ?? false;

        unset($validated['remember']);

        if (!Auth::attempt($validated, $remember)) {

            return new JsonResponse(data: [
                'message' => 'Credentials unauthorized',
            ], status: 403);
        }

        $user = Auth::user();

        $token = $user->createToken($user->position->name)->plainTextToken;

        return new JsonResponse(data: [
            'user' => $user,
            'token' => $token,
            'message' => 'Login successfully',
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();

        return new JsonResponse(data: [
            'message' => 'Logout successfully',
        ],);
    }
}
