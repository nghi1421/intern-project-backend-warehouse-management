<?php

namespace App\Http\Controllers;

use App\Http\Requests\Export\CreateExport;
use App\Http\Requests\Export\UpdateExport;
use App\Http\Resources\ExportResource;
use App\Models\Export;
use App\Models\Import;
use App\Models\Staff;
use App\Models\Stock;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class ExportController extends Controller
{
    public function index(Request $request): JsonResponse|AnonymousResourceCollection
    {
        $user = $request->user();

        $sortField = $request->input('sort_field', 'id');

        if (!in_array($sortField, [
            'id',
            'staff_name',
            'warehouse_branch_name',
            'destination_name',
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
            case 'warehouse_branch_name': {
                    $sortField = 'warehouse_branch_id';
                    break;
                }
            case 'destination_name': {
                    $sortField = 'to_warehouse_branch_id';
                    break;
                }
        }

        $sortDirection = $request->input('sort_direction', 'asc');

        if (!in_array($sortDirection, ['desc', 'asc'])) {
            $sortDirection = 'asc';
        }

        if ($user->can('manage-export')) {
            $query = Export::query();

            if ($searchTerm = $request->input('search')) {

                $query = $query->where('id', $searchTerm);

                $query = $query->orderBy($sortField, $sortDirection);

                return ExportResource::collection($query->paginate(5));
            }

            return ExportResource::collection($query
                ->orderBy($sortField, $sortDirection)
                ->paginate(5));
        }

        if ($user->can('read-branch-export')) {
            $staff = Staff::query()->where('user_id', $user->getKey())->firstOrFail();

            $query = Export::query()->where('warehouse_branch_id', $staff->warehouse_branch_id);

            if ($searchTerm = $request->input('search')) {

                $query = $query->where('id', $searchTerm);

                $query = $query->orderBy($sortField, $sortDirection);

                return ExportResource::collection($query->paginate(5));
            }

            return ExportResource::collection($query
                ->orderBy($sortField, $sortDirection)
                ->paginate(5));
        }

        return new JsonResponse(['message' => 'Forbidden'], 403);
    }


    public function store(CreateExport $request): JsonResponse
    {
        if ($request->user()->can('manage-export')) {
            if ($request->input('status') === 1) {

                $categories = $request->input('categories');

                $amounts = $request->input('amounts');

                $exportDetails = array_map(fn ($value) => [
                    'quantity' => $value,
                ], $amounts, array_keys($amounts));

                $exportDetails = array_combine($categories, $exportDetails);

                $newExport = Export::query()->create($request->validated());

                $newExport->categories()->attach($exportDetails);

                return new JsonResponse([
                    'message' => 'Create export successfully'
                ]);
            }

            return new JsonResponse([
                'message' => 'Status creating export muse be initial import.'
            ], 422);
        }

        return new JsonResponse(['message' => 'Forbidden'], 403);
    }

    public function show(string $id, Request $request): JsonResponse
    {
        if ($request->user()->canAny(['manage-export', 'read-branch-export'])) {
            $export =  Export::query()->findOrFail($id);

            return new JsonResponse(new ExportResource($export));
        }
        return new JsonResponse(['message' => 'Forbidden'], 403);
    }

    public function update(UpdateExport $request, string $id): JsonResponse
    {
        $user = $request->user();

        $export =  Export::query()->findOrFail($id);

        if ($export->status === 3) {
            return new JsonResponse(['message' => 'Export is done. Could not modify.']);
        }

        if ($user->can('update-export-status')) {
            if ($export->status === 1 && $request->input('status') === 2) {
                $export->update(['status' => 2]);
                return new JsonResponse(['message' => 'Switch to checking status successfully.']);
            } else if ($export->status === 2 && $request->input('status') === 3) {
                try {
                    if (!$export->update(['status' => 3])) {
                        return new JsonResponse(['message' => 'Update export failed'], 422);
                    }
                } catch (Exception $exception) {
                    return new JsonResponse(['message' => $exception->getMessage()], 422);
                }

                return new JsonResponse([
                    'message' => 'Export completed successfully.'
                ]);
            } else {

                return new JsonResponse([
                    'message' => 'You do not have permission to do this action.'
                ], 422);
            }
        }
        if ($user->can('manage-export')) {

            if ($export->status === 3) {
                return new JsonResponse(['message' => 'Export is done. Could not update import']);
            }

            if ($request->input('categories') && $request->input('amounts')) {
                $categories = $request->input('categories');

                $amounts = $request->input('amounts');

                $exportDetails = array_map(fn ($value) => [
                    'quantity' => $value,
                ], $amounts, array_keys($amounts));

                $exportDetails = array_combine($categories, $exportDetails);

                $export->categories()->sync($exportDetails);
            }

            $export->update($request->validated());

            return new JsonResponse([
                'message' => 'Update export successfully'
            ]);
        }
        return new JsonResponse(['message' => 'Forbidden'], 403);
    }


    public function destroy(string $id, Request $request): JsonResponse
    {
        if ($request->user()->can(['manage-export'])) {
            $export =  Export::query()->findOrFail($id);
            if ($export->status !== 1) {
                return new JsonResponse(['message' => 'Only delete initial export.'], 403);
            }
            try {
                if (!$export->delete()) {
                    return new JsonResponse([
                        'message' => 'Delete export failed',
                    ], 422);
                }
            } catch (Exception $exception) {
                return new JsonResponse([
                    'message' => $exception->getMessage(),
                ], 422);
            }

            return new JsonResponse([
                'message' => 'Delete export successfully'
            ]);
        }
        return new JsonResponse(['message' => 'Forbidden'], 403);
    }
}
