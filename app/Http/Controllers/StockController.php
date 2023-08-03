<?php

namespace App\Http\Controllers;

use App\Http\Requests\Stock\UpdateStock;
use App\Http\Resources\StockResource;
use App\Models\Import;
use App\Models\Staff;
use App\Models\Stock;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class StockController extends Controller
{
    public function index(Request $request): JsonResponse|AnonymousResourceCollection
    {
        $user = $request->user();

        if (
            ($user->can('manage-stock') && in_array($user->role->name, ['Nhan vien kho', 'Thu kho']))
            || $user->can('read-branch-stock')
        ) {
            $staff = Staff::query()->where('user_id', $user->getKey())->firstOrFail();

            $importBranchIds = Import::query()
                ->where('warehouse_branch_id', $staff->warehouse_branch_id)
                ->where('status', 3)
                ->pluck('id')
                ->toArray();

            $stocks = Stock::query()->whereIn('import_id', $importBranchIds)->paginate(5);

            return StockResource::collection($stocks);
        }

        if ($user->canAny(['read-stock', 'manage-stock'])) {
            return StockResource::collection(Stock::query()->paginate(5));
        }

        return new JsonResponse(['message' => 'Forbidden'], 403);
    }

    public function show(string $id, Request $request): JsonResponse|StockResource
    {
        $user = $request->user();
        $stock = Stock::query()->findOrFail($id);
        if (
            ($user->can('manage-stock') && in_array($user->role->name, ['Nhan vien kho', 'Thu kho']))
            || $user->can('read-branch-stock')
        ) {
            $staff = Staff::query()->where('user_id', $user->getKey())->firstOrFail();

            $importBranchIds = Import::query()
                ->where('warehouse_branch_id', $staff->warehouse_branch_id)
                ->where('status', 3)
                ->pluck('id')
                ->toArray();

            return in_array($stock->import_id, $importBranchIds)
                ? new StockResource($stock)
                : new JsonResource(['message' => 'Stock does in this warehouse branch'], 403);
        }

        if ($user->canAny(['read-stock', 'manage-stock'])) {
            return new StockResource($stock);
        }

        return new JsonResponse(['message' => 'Forbidden'], 403);
    }

    public function update(UpdateStock $request, string $id): JsonResponse
    {
        $user = $request->user();
        $stock = Stock::query()->findOrFail($id);

        if ($user->can('manage-stock') && in_array($user->role->name, ['Nhan vien kho', 'Thu kho'])) {
            $staff = Staff::query()->where('user_id', $user->getKey())->firstOrFail();

            $importBranchIds = Import::query()
                ->where('warehouse_branch_id', $staff->warehouse_branch_id)
                ->where('status', 3)
                ->pluck('id')
                ->toArray();

            if (!in_array($stock->import_id, $importBranchIds)) {
                return new JsonResource(['message' => 'Stock does in this warehouse branch'], 403);
            }

            try {
                if (!$stock->update($request->validated())) {
                    return new JsonResource(['message' => 'Updating stock failed'], 402);
                }
            } catch (Exception $exception) {
                return new JsonResource(['message' => $exception->getMessage()], 403);
            }
            return new JsonResource(['message' => 'Updating stock successfully']);
        }

        if ($user->can('manage-stock')) {
            try {
                if (!$stock->update($request->validated())) {
                    return new JsonResource(['message' => 'Updating stock failed'], 402);
                }
            } catch (Exception $exception) {
                return new JsonResource(['message' => $exception->getMessage()], 403);
            }
            return new JsonResource(['message' => 'Updating stock successfully']);
        }

        return new JsonResponse(['message' => 'Forbidden'], 403);
    }

    public function destroy(string $id, Request $request): JsonResponse
    {
        $user = $request->user();
        $stock = Stock::query()->findOrFail($id);

        if ($user->can('manage-stock') && in_array($user->role->name, ['Nhan vien kho', 'Thu kho'])) {
            $staff = Staff::query()->where('user_id', $user->getKey())->firstOrFail();

            $importBranchIds = Import::query()
                ->where('warehouse_branch_id', $staff->warehouse_branch_id)
                ->where('status', 3)
                ->pluck('id')
                ->toArray();

            if (!in_array($stock->import_id, $importBranchIds)) {

                return new JsonResource(['message' => 'Stock does in this warehouse branch.'], 403);
            }

            try {
                if (!$stock->delete()) {
                    return new JsonResource(['message' => 'Deleting stock failed'], 402);
                }
            } catch (Exception $exception) {
                return new JsonResource(['message' => $exception->getMessage()], 403);
            }

            return new JsonResource(['message' => 'Deleting stock successfully']);
        }

        if ($user->can('manage-stock')) {
            try {
                if (!$stock->delete()) {
                    return new JsonResource(['message' => 'Deleting stock failed'], 402);
                }
            } catch (Exception $exception) {
                return new JsonResource(['message' => $exception->getMessage()], 403);
            }

            return new JsonResource(['message' => 'Deleting stock successfully']);
        }

        return new JsonResponse(['message' => 'Forbidden'], 403);
    }
}
