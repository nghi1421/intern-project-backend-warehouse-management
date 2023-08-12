<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        if ($request->user()->can('read-role')) {
            return new JsonResponse(Role::query()
                ->select(['id', 'name'])
                ->with('permissions')
                ->get());
        }

        return new JsonResponse(['message' => 'Forbidden'], 403);
    }
}