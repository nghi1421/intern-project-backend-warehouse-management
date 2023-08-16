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

        $sortField = $request->input('sort_field', 'id');
        if (!in_array($sortField, ['id', 'category_name', 'import_id', 'expiry_date'])) {
            $sortField = 'id';
        }

        $sortDirection = $request->input('sort_direction', 'asc');
        if (!in_array($sortDirection, ['desc', 'asc'])) {
            $sortDirection = 'asc';
        }

        $searchColumns = $request->input('search_columns', ['id', 'import_id', 'expiry_date']);

        if (
            $user->canAny(['read-branch-stock', 'manage-branch-stock'])
        ) {
            $staff = Staff::query()->where('user_id', $user->getKey())->firstOrFail();
            $query = Stock::query();
            $importBranchIds = Import::query()
                ->where('warehouse_branch_id', $staff->warehouse_branch_id)
                ->where('status', 3)
                ->pluck('id')
                ->toArray();

            $query = Stock::query()->whereIn('import_id', $importBranchIds);

            if ($searchTerm = $request->input('search')) {

                $query = $query->where($searchColumns[0], $searchTerm);

                for ($i = 1; $i < count($searchColumns); $i++) {
                    $query = $query->orWhere($searchColumns[0], $searchTerm);
                }
                $query = $query->orderBy($sortField, $sortDirection);

                return StockResource::collection($query->paginate(5));
            }

            return StockResource::collection($query->orderBy($sortField, $sortDirection)->paginate(5));
        }

        if ($user->canAny(['read-stock', 'manage-stock'])) {
            $query = Stock::query();

            if ($searchTerm = $request->input('search')) {

                $query = $query->where($searchColumns[0], $searchTerm);

                for ($i = 1; $i < count($searchColumns); $i++) {
                    $query = $query->orWhere($searchColumns[0], $searchTerm);
                }
                $query = $query->orderBy($sortField, $sortDirection);

                return StockResource::collection($query->paginate(5));
            }

            return StockResource::collection($query->orderBy($sortField, $sortDirection)->paginate(5));
        }

        return new JsonResponse(['message' => 'Forbidden'], 403);
    }

    public function show(string $id, Request $request): JsonResponse|StockResource
    {
        $user = $request->user();
        $stock = Stock::query()->findOrFail($id);
        if (
            $user->can('manage-stock')
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

        if ($user->can('manage-branch-stock')) {
            $staff = Staff::query()->where('user_id', $user->getKey())->firstOrFail();

            $importBranchIds = Import::query()
                ->where('warehouse_branch_id', $staff->warehouse_branch_id)
                ->where('status', 3)
                ->pluck('id')
                ->toArray();

            if (!in_array($stock->import_id, $importBranchIds)) {
                return new JsonResponse(['message' => 'Stock does in this warehouse branch'], 403);
            }

            try {
                if (!$stock->update($request->validated())) {
                    return new JsonResponse(['message' => 'Updating stock failed'], 402);
                }
            } catch (Exception $exception) {
                return new JsonResponse(['message' => $exception->getMessage()], 403);
            }
            return new JsonResponse(['message' => 'Updating stock successfully']);
        }

        if ($user->can('manage-stock')) {
            try {
                if (!$stock->update($request->validated())) {
                    return new JsonResponse(['message' => 'Updating stock failed'], 402);
                }
            } catch (Exception $exception) {
                return new JsonResponse(['message' => $exception->getMessage()], 403);
            }
            return new JsonResponse(['message' => 'Updating stock successfully']);
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
