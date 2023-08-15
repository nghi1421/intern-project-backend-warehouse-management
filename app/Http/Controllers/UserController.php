<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\CreateUser;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection|JsonResponse
    {
        if ($request->user()->can('manage-account')) {

            $sortField = $request->input('sort_field', 'id');
            if (!in_array($sortField, ['id', 'username', 'created_at', 'updated_at'])) {
                $sortField = 'id';
            }

            if ($sortField === 'role_name') {
                $sortField = 'role_id';
            }

            $sortDirection = $request->input('sort_direction', 'asc');
            if (!in_array($sortDirection, ['desc', 'asc'])) {
                $sortDirection = 'asc';
            }

            $searchColumns = $request->input('search_columns', ['id', 'username']);

            if ($searchTerm = $request->input('search')) {

                $query = User::query();

                if ($searchColumns[0] === 'id') {
                    $query = $query->where($searchColumns[0], $searchTerm);
                } else {
                    $query = $query->where($searchColumns[0], 'LIKE', '%' . $searchTerm . '%');
                }

                for ($i = 1; $i < count($searchColumns); $i++) {
                    $query = $query->orWhere($searchColumns[$i], 'LIKE', '%' . $searchTerm . '%');
                }
                $query = $query->orderBy($sortField, $sortDirection);

                return UserResource::collection($query->paginate(5));
            } else {
                return UserResource::collection(User::query()->orderBy($sortField, $sortDirection)->paginate(5));
            }
        }

        return new JsonResponse(['message' => 'Forbidden'], 403);
    }

    public function store(CreateUser $request): JsonResponse
    {
        if ($request->user()->can('manage-account')) {
            $newUser = User::query()->create($request->validated());

            if (!$newUser) {
                return new JsonResponse(['message' => 'Creating account failed'], 422);
            }

            $newUser->permissions()->attach($request->input('permissions'));

            return new JsonResponse(['message' => 'Account create successfully']);
        }

        return new JsonResponse(['message' => 'Forbidden'], 403);
    }

    public function show(string $id, Request $request): JsonResponse
    {
        if ($request->user()->can('manage-account')) {
            $user = User::query()->findOrFail($id);

            return new JsonResponse([...$user->toArray(), 'permissions' => $user->permissions]);
        }

        return new JsonResponse(['message' => 'Forbidden'], 403);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        if ($request->user()->can('manage-account')) {
            $user = User::query()->findOrFail($id);

            if ($request->input('reset-password')) {
                if (!$user->update(['passsword' => '123123123'])) {
                    return new JsonResponse(['message' => 'Account update failed']);
                }

                return new JsonResponse(['message' => 'Password account reseted successfully']);
            }

            $validated = Validator::make($request->all(), [
                'username' => ['sometimes', 'min:8', 'max:255', Rule::unique('users', 'username')->ignore($user)],
                'password' => ['sometimes', 'confirmed', 'min:8', 'max:255'],
                'role_id' => ['sometimes', 'exists:roles,id'],
                'permissions' => ['sometimes', 'array', 'min:1'],
                'permissions.*' => ['sometimes', 'exists:permissions,id']
            ])->validated();

            if (!$user->update($validated)) {
                return new JsonResponse(['message' => 'Account update failed']);
            }

            $user->sync($request->input('permissions'));

            return new JsonResponse(['message' => 'Account update successfully']);
        }

        return new JsonResponse(['message' => 'Forbidden'], 403);
    }


    public function destroy(string $id, Request $request): JsonResponse
    {
        if ($request->user()->can('manage-account')) {
            $user = User::query()->findOrFail($id);
            if ($request->user()->getKey() == $id) {
                return new JsonResponse(['message' => 'You could not delete your account'], 422);
            }

            if (!$user->delete()) {
                return new JsonResponse(['message' => 'Account deleted fail']);
            }

            return new JsonResponse(['message' => 'Account deleted successfully']);
        }

        return new JsonResponse(['message' => 'Forbidden'], 403);
    }
}
