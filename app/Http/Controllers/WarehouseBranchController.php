<?php

namespace App\Http\Controllers;

use App\Http\Requests\WarehouseBranch\CreateWarehouseBranch;
use App\Http\Resources\WarehouseBranchResource;
use App\Models\WarehouseBranch;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class WarehouseBranchController extends Controller
{
    public function index(Request $request): JsonResponse|AnonymousResourceCollection
    {
        $user = $request->user();

        if ($user->canAny(['manage-warehouse-branch', 'read-warehouse-branch'])) {
            $request->validate([
                'no_pagination' => ['nullable', 'boolean'],
            ]);

            if ($request->input('no_pagination')) {
                return WarehouseBranchResource::collection(WarehouseBranch::query()->get());
            }

            return WarehouseBranchResource::collection(WarehouseBranch::query()->paginate(5));
        }

        return new JsonResponse(['message' => 'Forbidden'], 403);
    }


    public function store(CreateWarehouseBranch $request): JsonResponse
    {
        $user = $request->user();

        if ($user->can('manage-warehouse-branch')) {
            try {
                WarehouseBranch::query()->create($request->validated());
            } catch (Exception $exception) {
                return new JsonResponse([
                    'message' => $exception->getMessage(),
                ], 422);
            }

            return new JsonResponse([
                'message' => 'Warehouse branch created successfully',
            ]);
        }

        return new JsonResponse([
            'message' => 'Forbidden',
        ], 403);
    }

    public function show(string $id, Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->canAny(['manage-warehouse-branch', 'read-warehouse-branch'])) {
            $warehouseBrach = WarehouseBranch::query()->findOrFail($id);

            return new JsonResponse($warehouseBrach);
        }

        return new JsonResponse([
            'message' => 'Forbidden',
        ], 403);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $warehouseBranch = WarehouseBranch::query()->findOrFail($id);

        $validated = Validator::make(
            $request->all(),
            [
                'name' => [
                    'required',
                    'string',
                    'max:50',
                    Rule::unique('warehouse_branches', 'name')->ignore($warehouseBranch),
                ],
                'address' => [
                    'required',
                    'max:255'
                ],
                'phone_number' => [
                    'required',
                    'max:15',
                    'regex:/^(0?)(3[2-9]|5[6|8|9]|7[0|6-9]|8[0-6|8|9]|9[0-4|6-9])[0-9]{7}$/',
                    Rule::unique('warehouse_branches', 'phone_number')->ignore($warehouseBranch),
                ],
                'opening' => [
                    'required',
                    'boolean',
                ]
            ]
        )->validated();

        $user = $request->user();

        if ($user->can('manage-warehouse-branch')) {
            try {
                if (!$warehouseBranch->update($validated)) {
                    return new JsonResponse([
                        'message' => 'Warehouse branch update failed'
                    ], 422);
                }
            } catch (Exception $e) {
                return new JsonResponse([
                    'message' => $e->getMessage(),
                ]);
            }
            return new JsonResponse(['message' => 'Warehouse branch updated successfully']);
        }

        return new JsonResponse([
            'message' => 'Forbidden',
        ], 403);
    }

    public function destroy(string $id, Request $request): JsonResponse
    {
        $user = $request->user();

        $warehouseBranch = WarehouseBranch::query()->findOrFail($id);

        if ($user->can('manage-warehouse-branch')) {
            if ($warehouseBranch->staffs->count() > 0) {
                return new JsonResponse([
                    'message' => 'Warehouse branch has staff. Could not delete warehouse branch'
                ], 422);
            }

            if ($warehouseBranch->imports->count() > 0) {
                return new JsonResponse([
                    'message' => 'Warehouse branch has imports. Could not delete warehouse branch'
                ], 422);
            }

            if ($warehouseBranch->exports->count() > 0) {
                return new JsonResponse([
                    'message' => 'Warehouse branch has exports. Could not delete warehouse branch'
                ], 422);
            }
            try {
                if ($warehouseBranch->delete()) {
                    return new JsonResponse([
                        'message' => 'Warehouse branch delete failed'
                    ], 422);
                }
            } catch (Exception $e) {
                return new JsonResponse([
                    'message' => $e->getMessage(),
                ]);
            }

            return new JsonResponse(['message' => 'Warehouse branch deleted successfully']);
        }

        return new JsonResponse([
            'message' => 'Forbidden',
        ], 403);
    }
}
