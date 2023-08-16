<?php

namespace App\Http\Controllers;

use App\Http\Requests\Position\CreatePosition;
use App\Http\Resources\PositionResource;
use App\Models\Position;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PositionController extends Controller
{
    public function index(Request $request): JsonResponse|AnonymousResourceCollection
    {
        $user = $request->user();

        $request->validate([
            'no_pagination' => ['nullable', 'boolean'],
        ]);

        if ($user->canAny(['manage-position', 'read-position'])) {

            $sortField = $request->input('sort_field', 'id');
            if (!in_array($sortField, ['id', 'name', 'created_at', 'updated_at'])) {
                $sortField = 'id';
            }

            $sortDirection = $request->input('sort_direction', 'asc');
            if (!in_array($sortDirection, ['desc', 'asc'])) {
                $sortDirection = 'asc';
            }

            $searchColumns = $request->input('search_columns', ['id', 'name']);

            if ($request->input('no_pagination')) {
                return PositionResource::collection(Position::query()->get());
            }

            if ($searchTerm = $request->input('search')) {

                $query = Position::query();

                if ($searchColumns[0] === 'id') {
                    $query = $query->where($searchColumns[0], $searchTerm);
                } else {
                    $query = $query->where($searchColumns[0], 'LIKE', '%' . $searchTerm . '%');
                }

                for ($i = 1; $i < count($searchColumns); $i++) {
                    $query = $query->orWhere($searchColumns[$i], 'LIKE', '%' . $searchTerm . '%');
                }
                $query = $query->orderBy($sortField, $sortDirection);

                return PositionResource::collection($query->paginate(5));
            }

            return PositionResource::collection(Position::query()->orderBy($sortField, $sortDirection)->paginate(5));
        }

        return new JsonResponse(['message' => 'Forbidden'], 403);
    }

    public function show(string $id, Request $request): JsonResponse
    {
        if ($request->user()->canAny(['manage-position', 'read-position'])) {
            $position = Position::query()->findOrFail($id);

            return new JsonResponse($position);
        }
        return new JsonResponse(['message' => 'Forbidden'], 403);
    }

    public function store(CreatePosition $request): JsonResponse
    {
        if ($request->user()->can('manage-position')) {
            try {
                Position::query()->create($request->validated());
            } catch (Exception $exception) {
                return new JsonResponse([
                    'message' => $exception->getMessage(),
                ], 422);
            }

            return new JsonResponse(['message' => 'Position created successfully.']);
        }
        return new JsonResponse(['message' => 'Forbidden'], 403);
    }


    public function update(Request $request, string $id): JsonResponse
    {
        if ($request->user()->can('manage-position')) {
            $position = Position::query()->findOrFail($id);

            $validated = Validator::make($request->all(), [
                'name' => [
                    'required',
                    Rule::unique('positions', 'name')->ignore($position)
                ]
            ])->validated();

            try {
                Position::query()->update($validated);
            } catch (Exception $exception) {
                return new JsonResponse([
                    'message' => $exception->getMessage(),
                ], 422);
            }

            return new JsonResponse(['message' => 'Position updated successfully.']);
        }
        return new JsonResponse(['message' => 'Forbidden'], 403);
    }


    public function destroy(string $id, Request $request): JsonResponse
    {
        if ($request->user()->can('manage-position')) {
            $position = Position::query()->findOrFail($id);
            if ($position->staffs->count() > 0) {
                return new JsonResponse([
                    'message' => 'Position is being used.',
                ], 422);
            }
            try {
                if (!$position->delete()) {
                    return new JsonResponse([
                        'message' => 'Deleting position failed.',
                    ], 422);
                }
            } catch (Exception $exception) {
                return new JsonResponse([
                    'message' => $exception->getMessage(),
                ], 422);
            }

            return new JsonResponse(['message' => 'Position deleted successfully.']);
        }
        return new JsonResponse(['message' => 'Forbidden'], 403);
    }
}
