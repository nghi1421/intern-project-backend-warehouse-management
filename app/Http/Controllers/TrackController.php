<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\Import;
use App\Models\WarehouseBranch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TrackController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->role->name === 'Nhan vien quan li') {
            $warehouseBranches = WarehouseBranch::query()->get();

            $dataTrack = [];

            foreach ($warehouseBranches as $warehouse) {
                $importIds = Import::query()->where('warehouse_branch_id', $warehouse->getKey())->pluck('id');
                $categories = Category::query()
                    ->with(['stocks' => function ($query) use ($importIds) {
                        $query->whereIn('import_id', $importIds);
                    }])->get();

                $categoryResponse = [];

                foreach ($categories as $category) {
                    $categoryResponse[] = [
                        'quantity' => $category->stocks->sum('quantity'),
                    ];
                }

                $dataTrack[] = [
                    'branch_name' => $warehouse->name,
                    'categories' => $categoryResponse,
                ];
            }

            return new JsonResponse([
                'categories' => Category::query()->get(),
                'data' => $dataTrack
            ]);
        }
        return new JsonResponse('Fobidden', 403);
    }
}
