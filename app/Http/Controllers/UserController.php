<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection|JsonResponse
    {
        if ($request->user()->can('manage-user')) {
            return UserResource::collection(User::query()->paginate(5));
        }

        return new JsonResponse(['message' => 'Forbidden'], 403);
    }

    public function store(Request $request)
    {
    }

    public function show(string $id)
    {
    }

    public function update(Request $request, string $id)
    {
    }


    public function destroy(string $id)
    {
    }
}