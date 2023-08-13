<?php

namespace App\Http\Controllers;

use App\Http\Requests\Import\CreateImport;
use App\Http\Requests\Import\UpdateImport;
use App\Http\Resources\ImportCollection;
use App\Http\Resources\ImportResource;
use App\Models\Import;
use App\Models\Staff;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ImportController extends Controller
{
    public function index(Request $request): ImportCollection|JsonResponse
    {
        $user = $request->user();

        if ($user->can('manage-import')) {
            return new ImportCollection(Import::query()->paginate(5));
        }

        if ($user->can('read-branch-import')) {
            $staff = Staff::query()->where('user_id', $user->getKey())->firstOrFail();
            $imports = Import::query()->where('warehouse_branch_id', $staff->warehouse_branch_id)->paginate(5);

            return new ImportCollection($imports);
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
}
