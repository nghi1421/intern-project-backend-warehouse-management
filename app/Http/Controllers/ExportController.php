<?php

namespace App\Http\Controllers;

use App\Http\Requests\Export\CreateExport;
use App\Http\Requests\Export\UpdateExport;
use App\Http\Resources\ExportResource;
use App\Models\Export;
use App\Models\Staff;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ExportController extends Controller
{
    public function index(Request $request): JsonResponse|AnonymousResourceCollection
    {
        $user = $request->user();

        if ($user->can('manage-export')) {
            return ExportResource::collection(Export::query()->paginate(5));
        }

        if ($user->can('read-branch-export')) {
            $staff = Staff::query()->where('user_id', $user->getKey())->firstOrFail();
            $exports = Export::query()->where('warehouse_branch_id', $staff->warehouse_branch_id)->paginate(5);

            return ExportResource::collection($exports);
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

                $newExport->categories()->sync($exportDetails);

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
            return new JsonResponse(['message' => 'Export is done. Could not']);
        }

        if ($user->can('update-export-status')) {
            if ($export->status === 1 && $request->input('status') === 2) {
                $export->update(['status' => 2]);

                return new JsonResponse(['message' => 'Switch to checking status successfully.']);
            }

            if ($export->status === 2 && $request->input('status') === 3) {
                $export->update(['status' => 3]);
                return new JsonResponse(['message' => 'Export completed successfully.']);
            }

            return new JsonResponse(['message' => 'You do not have permission to do this action.']);
        }
        if ($user->can('manage-export')) {

            if ($export->status === 3) {
                return new JsonResponse(['message' => 'Export is done. Could not update import']);
            }

            $categories = $request->input('categories');

            $amounts = $request->input('amounts');

            $exportDetails = array_map(fn ($value) => [
                'quantity' => $value,
            ], $amounts, array_keys($amounts));

            $exportDetails = array_combine($categories, $exportDetails);

            $export->categories()->sync($exportDetails);

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
