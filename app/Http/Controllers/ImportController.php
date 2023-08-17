<?php

namespace App\Http\Controllers;

use App\Http\Requests\Import\CreateImport;
use App\Http\Requests\Import\UpdateImport;
use App\Http\Resources\ImportCollection;
use App\Http\Resources\ImportResource;
use App\Models\Enums\ExportStatus;
use App\Models\Enums\ImportStatus;
use App\Models\Export;
use App\Models\Import;
use App\Models\Staff;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class ImportController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection|JsonResponse
    {
        $user = $request->user();

        $sortField = $request->input('sort_field', 'id');

        if (!in_array($sortField, [
            'id',
            'staff_name',
            'provided_from',
            'warehouse_branch_name',
            'status',
            'created_at',
            'updated_at'
        ])) {
            $sortField = 'id';
        }

        switch ($sortField) {
            case 'staff_name': {
                    $sortField = 'staff_id';
                    break;
                }
            case 'provided_from': {
                    $sortField = 'provider_id';
                    break;
                }
            case 'warehouse_branch_name': {
                    $sortField = 'warehouse_branch_id';
                    break;
                }
        }

        $sortDirection = $request->input('sort_direction', 'asc');

        if (!in_array($sortDirection, ['desc', 'asc'])) {
            $sortDirection = 'asc';
        }

        if ($user->can('manage-import')) {
            $query = Import::query();

            if ($searchTerm = $request->input('search')) {

                $query = $query->where('id', $searchTerm);

                $query = $query->orderBy($sortField, $sortDirection);

                return ImportResource::collection($query->paginate(5));
            }

            return ImportResource::collection($query
                ->orderBy($sortField, $sortDirection)
                ->paginate(5));
        }

        if ($user->can('read-branch-import')) {
            $staff = Staff::query()->where('user_id', $user->getKey())->firstOrFail();

            $query = Import::query()->where('warehouse_branch_id', $staff->warehouse_branch_id);

            if ($searchTerm = $request->input('search')) {

                $query = $query->where('id', $searchTerm);

                $query = $query->orderBy($sortField, $sortDirection);

                return ImportResource::collection($query->paginate(5));
            }

            return ImportResource::collection($query
                ->orderBy($sortField, $sortDirection)
                ->paginate(5));
        }

        return new JsonResponse(['message' => 'Forbidden'], 403);
    }


    public function store(CreateImport $request): JsonResponse
    {
        if ($request->user()->can('manage-import')) {
            if ($request->input('status') === 1) {
                try {
                    DB::beginTransaction();

                    $categories = $request->input('categories');

                    $amounts = $request->input('amounts');

                    $unitPrices = $request->input('unit_prices');

                    $importDetails = array_map(fn ($value, $index) => [
                        'quantity' => $value,
                        'unit_price' => $unitPrices[$index]
                    ], $amounts, array_keys($amounts));

                    $importDetails = array_combine($categories, $importDetails);

                    $newImport = Import::query()->create($request->validated());

                    $newImport->categories()->sync($importDetails);

                    DB::commit();
                } catch (Exception $exception) {
                    DB::rollback();

                    return new JsonResponse([
                        'message' => $exception->getMessage(),
                    ], 422);
                }

                return new JsonResponse([
                    'message' => 'Create import successfully'
                ]);
            }

            return new JsonResponse([
                'message' => 'Status creating import muse be initial import'
            ], 422);
        }

        return new JsonResponse(['message' => 'Forbidden'], 403);
    }

    public function show(string $id, Request $request): JsonResponse
    {
        if ($request->user()->canAny(['manage-import', 'read-branch-import'])) {
            $import =  Import::query()->findOrFail($id);

            return new JsonResponse(new ImportResource($import));
        }
        return new JsonResponse(['message' => 'Forbidden'], 403);
    }

    public function update(UpdateImport $request, string $id): JsonResponse
    {
        $user = $request->user();

        $import =  Import::query()->findOrFail($id);

        if ($import->status === 3) {
            return new JsonResponse(['message' => 'Import is done. Could not']);
        }

        if ($user->can('cancel-import')) {
            if ($request->input('status') === 0) {
                if ($import->status === 1) {
                    $import->update(['status' => 0]);
                    return new JsonResponse(['message' => 'Import cancel successfully.']);
                } else {
                    return new JsonResponse(['message' => 'Could not cancel import.']);
                }
            }
        }

        if ($user->can('update-import-status')) {

            if ($import->status === 1 && $request->input('status') === 2) {
                $import->update(['status' => 2]);
                return new JsonResponse(['message' => 'Switch to checking status successfully.']);
            } else if ($import->status === 2 && $request->input('status') === 3) {
                $import->update(['status' => 3]);
                return new JsonResponse(['message' => 'Import completed successfully.']);
            } else {
                return new JsonResponse(['message' => 'You do not have permission to do this action.']);
            }
        }
        if ($user->can('manage-import')) {

            if ($import->status === 3) {
                return new JsonResponse(['message' => 'Import is done. Could not']);
            }

            try {
                DB::beginTransaction();
                if ($request->input('categories') && $request->input('amounts') && $request->input('unit_prices')) {
                    $categories = $request->input('categories');

                    $amounts = $request->input('amounts');

                    $unitPrices = $request->input('unit_prices');

                    $importDetails = array_map(fn ($value, $index) => [
                        'quantity' => $value,
                        'unit_price' => $unitPrices[$index]
                    ], $amounts, array_keys($amounts));

                    $importDetails = array_combine($categories, $importDetails);

                    $import->categories()->sync($importDetails);
                }

                $import->update($request->validated());

                DB::commit();
            } catch (Exception $exception) {
                DB::rollback();

                return new JsonResponse([
                    'message' => $exception->getMessage(),
                ], 422);
            }

            return new JsonResponse([
                'message' => 'Update import successfully'
            ]);
        }
        return new JsonResponse(['message' => 'Forbidden'], 403);
    }


    public function destroy(string $id, Request $request): JsonResponse
    {
        if ($request->user()->can(['manage-import'])) {
            $import =  Import::query()->findOrFail($id);
            if ($import->status !== 1) {
                return new JsonResponse(['message' => 'Only delete initial import.'], 403);
            }
            try {
                if (!$import->delete()) {
                    return new JsonResponse([
                        'message' => 'Delete import failed',
                    ], 422);
                }
            } catch (Exception $exception) {
                return new JsonResponse([
                    'message' => $exception->getMessage(),
                ], 422);
            }

            return new JsonResponse([
                'message' => 'Delete import successfully'
            ]);
        }
        return new JsonResponse(['message' => 'Forbidden'], 403);
    }

    public function log(Request $request): JsonResponse
    {
        $user = $request->user();

        $request->validate([
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date']
        ]);

        $dateRange = [
            Carbon::create($request->input('start_date'))->startOfDay(),
            Carbon::create($request->input('end_date'))->endOfDay()
        ];

        $staff = Staff::query()->where('user_id', $user->getKey())->firstOrFail();

        if ($user->role->name === 'Nhan vien quan li') {
            $imports = Import::query()
                ->whereBetween('created_at', $dateRange)
                ->with('categories')
                ->get();

            $exports = Export::query()
                ->whereBetween('created_at', $dateRange)
                ->with('categories')
                ->get();
        } else {
            $imports = Import::query()
                ->whereBetween('created_at', $dateRange)
                ->where('warehouse_branch_id', $staff->warehouse_branch_id)
                ->with('categories')
                ->get();

            $exports = Export::query()
                ->whereBetween('created_at', $dateRange)
                ->where('warehouse_branch_id', $staff->warehouse_branch_id)
                ->with('categories')
                ->get();
        }

        $dataLog = [];

        foreach ($imports as $import) {
            $fromWarehouseBranch = $import->fromWarehouseBranch;

            $categories = array_map(function ($category) {
                $quantity = $category['pivot']['quantity'];
                unset($category['pivot']);
                return [
                    ...$category,
                    'amount' => number_format($quantity, 0, ".", ","),
                ];
            }, $import->categories->toArray());

            $sum = collect($import->categories->toArray())->reduce(function ($sum, $category) {
                return $sum + $category['pivot']['quantity'] * $category['pivot']['unit_price'];
            }, 0);

            $dataLog[] = [
                'is_import' => 1,
                'from' => $import->provider ? $import->provider->name : $fromWarehouseBranch->name,
                'to' => $import->warehouseBranch->name,
                'status_id' => $import->status,
                'status' => ImportStatus::tryFrom($import->status)->label(),
                'detail' =>  $categories,
                'type' => $fromWarehouseBranch ? 'Chuyển kho' : 'Nhập hàng',
                'total' => number_format($sum, 0, ".", ",") . ' VND',
                'created_at' => $import->created_at->format('m/d/Y'),
            ];
        }

        foreach ($exports as $export) {
            $toWarehouseBranch = $export->toWarehouseBranch;

            $categories = array_map(function ($category) {
                $quantity = $category['pivot']['quantity'];
                unset($category['pivot']);
                return [
                    ...$category,
                    'amount' => number_format($quantity, 0, ".", ","),
                ];
            }, $export->categories->toArray());

            $dataLog[] = [
                'is_import' => 0,
                'from' => $export->warehouseBranch->name,
                'to' => $toWarehouseBranch ? $toWarehouseBranch->name : 'Cửa hàng',
                'status_id' => $import->status,
                'status' => ExportStatus::tryFrom($export->status)->label(),
                'detail' =>  $categories,
                'type' => $toWarehouseBranch ? 'Chuyển kho' : 'Xuất hàng',
                'total' => 0 . ' VND',
                'created_at' => $export->created_at->format('m/d/Y'),
            ];
        }

        usort($dataLog, function ($a, $b) {
            return strtotime($a['created_at']) - strtotime($b['created_at']);
        });

        return new JsonResponse($dataLog);
    }
}
