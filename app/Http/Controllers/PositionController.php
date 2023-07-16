<?php

namespace App\Http\Controllers;

use App\Http\Requests\Position\CreatePosition;
use App\Models\Position;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class PositionController extends Controller
{
    public function index(): JsonResponse
    {
        return new JsonResponse([
            'positions' => Position::query()->get(),
            'pagination' => Position::query()->paginate(5)
        ]);
    }

    public function show(Position $position): JsonResponse
    {
        return new JsonResponse($position);
    }

    public function store(CreatePosition $request): JsonResponse
    {
        try {
            Position::query()->create($request->validated());
        } catch (Exception $exception) {
            return new JsonResponse([
                'message' => $exception->getMessage(),
            ], 422);
        }

        return new JsonResponse(['message' => 'Position created successfully.']);
    }


    public function update(Request $request, Position $position): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'unique.positions,name,' . $position->name]
        ]);

        try {
            Position::query()->update($request->validated());
        } catch (Exception $exception) {
            return new JsonResponse([
                'message' => $exception->getMessage(),
            ], 422);
        }

        return new JsonResponse(['message' => 'Position updated successfully.']);
    }


    public function destroy(Position $position): JsonResponse
    {
        if ($position->staffs->count() > 0) {
            return new JsonResponse([
                'message' => 'Position is being used.',
            ], 422);
        }
        try {
            $result = Position::query()->delete();
            if (!$result) {
                return new JsonResponse([
                    'message' => 'Deleting position failed.',
                ], 422);
            }
        } catch (Exception $exception) {
            return new JsonResponse([
                'message' => $exception->getMessage(),
            ], 422);
        }

        return new JsonResponse(['message' => 'Position updated successfully.']);
    }
}
