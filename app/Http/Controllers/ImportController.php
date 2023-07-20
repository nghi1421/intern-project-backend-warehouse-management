<?php

namespace App\Http\Controllers;

use App\Http\Requests\Import\CreateImport;
use App\Http\Resources\ImportCollection;
use App\Http\Resources\ImportResource;
use App\Models\Import;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ImportController extends Controller
{
    public function index(): JsonResponse
    {
        return new JsonResponse([
            'imports' => new ImportCollection(Import::query()->get()),
            'pagination' => new ImportCollection(Import::query()->paginate(5)),
        ]);
    }

    public function store(CreateImport $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $categories = $request->input('categories');

            $amounts = $request->input('amounts');

            array_walk($amounts, function (&$item) {
                $item = ['amount' => $item];
            });

            $newImport = Import::query()->create($request->validated());

            $newImport->categories()->sync(array_combine($categories, $amounts));

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

    public function show(string $id): JsonResponse
    {
        $import =  Import::query()->find($id);

        if (!$import) {
            return new JsonResponse([
                'message' => 'Import not found',
            ], 404);
        }

        return new JsonResponse(new ImportResource($import));
    }

    public function update(Request $request, string $id)
    {
    }


    public function destroy(string $id)
    {
    }
}
