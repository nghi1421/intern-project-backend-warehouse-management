<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\Staff;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

        try {
            $staff = Staff::query()->where('user_id', $user->getKey())->firstOrFail();
        } catch (ModelNotFoundException $exception) {

            return new JsonResponse(data: [
                'message' => 'Staff infomation not found',
            ], status: 400);
        }

        $token = $user->createToken($user->role->name)->plainTextToken;

        return new JsonResponse(data: [
            'staff_information' => $staff,
            'role' => $user->role->name,
            'permissions' => $user->role->permissions->toArray(),
            'token' => $token,
            'message' => 'Login successfully',
        ]);
    }

    public function getPermission(Request $request): JsonResponse
    {
        $user = $request->user();

        $permissions = $user->role->permissions->toArray();

        return new JsonResponse($permissions);
    }

    public function changePassword(Request $request): JsonResponse
    {
        $user = $request->user();

        $request->validate([
            'old_password' => ['required', 'max:255'],
            'new_password' => ['required', 'confirmed', 'max:255'],
        ]);

        if (!Hash::check($request->input('old_password'), $user->password)) {

            return new JsonResponse([
                'message' => 'Change password failed',
            ], 422);
        }

        $user->password = bcrypt($request->input('new_password'));

        if (!$user->update()) {
            return new JsonResponse([
                'message' => 'Change password failed',
            ], 422);
        }

        return new JsonResponse([
            'message' => 'Change password successfully',
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
