<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        if ($request->user()->can('read-permission')) {
            return new JsonResponse(Permission::query()->select(['id', 'name', 'description'])->get());
        }

        return new JsonResponse(['message' => 'Forbidden'], 403);
    }
}