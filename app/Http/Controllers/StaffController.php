<?php

namespace App\Http\Controllers;

use App\Http\Requests\Staff\CreateStaff;
use App\Http\Resources\StaffCollection;
use App\Models\Staff;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

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

    public function update(Request $request, Staff $staff): JsonResponse
    {
        $validated = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:255'
            ],
            'phone_number' => [
                'required',
                'string',
                'regex:/^(0?)(3[2-9]|5[6|8|9]|7[0|6-9]|8[0-6|8|9]|9[0-4|6-9])[0-9]{7}$/',
                Rule::unique('staffs', 'phone_number')->ignore($staff),
            ],
            'avatar' => [
                'nullable',
                'image',
                File::types(['jpg', 'png', 'jpeg'])->max(1024 * 20),
            ],
            'address' => [
                'required',
                'string',
                'max:200',
            ],
            'gender' => [
                'required',
                Rule::in([0, 1, 2]),
            ],
            'position_id' => [
                'required',
                'numeric',
                'exists:positions,id'
            ],
            'user_id' => [
                'nullable',
                'exists:users,id'
            ],
            'dob' => [
                'nullable',
                'date',
            ],
            'working' => [
                'required',
                'boolean',
            ]
        ])->validate();

        try {
            if (!$staff->update($validated)) {
                return new JsonResponse([
                    'message' => 'Update staff failed',
                ], 422);
            }
        } catch (Exception $exception) {
            return new JsonResponse([
                'message' => $exception->getMessage(),
            ], 422);
        }

        return new JsonResponse(['message' => 'Staff successfully updated.']);
    }

    public function destroy(Staff $staff): JsonResponse
    {
        if ($staff->imports->count() > 0) {
            return new JsonResponse([
                'message' => 'Could not delete staff, created import',
            ], 422);
        }

        if ($staff->exports->count() > 0) {
            return new JsonResponse([
                'message' => 'Could not delete staff, created export',
            ], 422);
        }

        try {
            if (!$staff->delete()) {
                return new JsonResponse([
                    'message' => 'Delete staff failed',
                ], 422);
            }
        } catch (Exception $exception) {
            return new JsonResponse([
                'message' => $exception->getMessage(),
            ], 422);
        }

        return new JsonResponse(['message' => 'Staff successfully deleted.']);
    }
}
