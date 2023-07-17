<?php

namespace App\Http\Controllers;

use App\Models\WarehouseBranch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WarehouseBranchController extends Controller
{
    public function index(): JsonResponse
    {
        return new JsonResponse([
            'pagination' => WarehouseBranch::query()->paginate(5),
            'warehouse_branches' => WarehouseBranch::query()->get(),
        ]);
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