<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserCollection;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request): UserCollection|JsonResponse
    {
        if ($request->user()->can('manage_all_user')) {
            return new UserCollection(User::query()->paginate(5));
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
