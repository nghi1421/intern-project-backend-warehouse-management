<?php

namespace App\Http\Controllers;

use App\Http\Requests\Staff\CreateStaff;
use App\Http\Requests\Staff\UpdateStaff;
use App\Http\Resources\StaffCollection;
use App\Models\Staff;
use Exception;
use Illuminate\Http\JsonResponse;

class StaffController extends Controller
{
    public function index(): StaffCollection
    {
        return new StaffCollection(Staff::query()->paginate(5));
    }

    public function show(Staff $staff): JsonResponse
    {
        return new JsonResponse($staff);
    }

    public function store(CreateStaff $request): JsonResponse
    {
        try {
            Staff::query()->create($request->validated());
        } catch (Exception $exception) {
            return new JsonResponse([
                'message' => $exception->getMessage(),
            ], 422);
        }

        return new JsonResponse(['message' => 'Staff successfully created.']);
    }

    public function update(UpdateStaff $request, Staff $staff): JsonResponse
    {
        try {
            $staff->update($request->validated());
        } catch (Exception $exception) {
            return new JsonResponse([
                'message' => $exception->getMessage(),
            ], 422);
        }

        return new JsonResponse(['message' => 'Staff successfully updated.']);
    }

    public function destroy(Staff $staff): JsonResponse
    {
        try {
            $staff->delete();
        } catch (Exception $exception) {
            return new JsonResponse([
                'message' => $exception->getMessage(),
            ], 422);
        }

        return new JsonResponse(['message' => 'Staff successfully deleted.']);
    }
}
