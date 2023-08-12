<?php

namespace App\Http\Controllers;

use App\Http\Requests\Position\CreatePosition;
use App\Models\Position;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PositionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->can('manage-position')) {
            return new JsonResponse([
                'positions' => Position::query()->select(['id', 'name'])->get(),
                'pagination' => Position::query()->paginate(5)
            ]);
        }

        return new JsonResponse(['message' => 'Forbidden'], 403);
    }

    public function show(string $id, Request $request): JsonResponse
    {
        if ($request->user()->can('manage-position')) {
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
                if (!$position::query()->delete()) {
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
