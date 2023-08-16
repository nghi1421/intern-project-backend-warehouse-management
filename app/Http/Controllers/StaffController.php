<?php

namespace App\Http\Controllers;

use App\Http\Requests\Staff\CreateStaff;
use App\Http\Resources\StaffResource;
use App\Models\Staff;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection|JsonResponse
    {
        $user = $request->user();

        $sortField = $request->input('sort_field', 'id');
        if (!in_array($sortField, ['id', 'name', 'position', 'working', 'gender', 'phone_number'])) {
            $sortField = 'id';
        }

        switch ($sortField) {
            case 'position': {
                    $sortField = 'position_id';
                    break;
                }
            case 'status': {
                    $sortField = 'working';
                    break;
                }
        }

        $sortDirection = $request->input('sort_direction', 'asc');
        if (!in_array($sortDirection, ['desc', 'asc'])) {
            $sortDirection = 'asc';
        }

        $searchColumns = $request->input('search_columns', ['id', 'name', 'phone_number']);

        if ($user->can('manage-all-staff')) {
            if ($searchTerm = $request->input('search')) {

                $query = Staff::query();

                if ($searchColumns[0] === 'id') {
                    $query = $query->where($searchColumns[0], $searchTerm);
                } else {
                    $query = $query->where($searchColumns[0], 'LIKE', '%' . $searchTerm . '%');
                }

                for ($i = 1; $i < count($searchColumns); $i++) {
                    $query = $query->orWhere($searchColumns[$i], 'LIKE', '%' . $searchTerm . '%');
                }
                $query = $query->orderBy($sortField, $sortDirection);

                return StaffResource::collection($query->paginate(5));
            }

            return StaffResource::collection(Staff::query()->orderBy($sortField, $sortDirection)->paginate(5));
        }

        if ($user->can('manage-branch-staff')) {
            $staff = Staff::query()->where('user_id', $user->getKey())->firstOrFail();

            if ($searchTerm = $request->input('search')) {

                $query = Staff::query()->where('warehouse_branch_id', $staff->warehouse_branch_id);

                if ($searchColumns[0] === 'id') {
                    $query = $query->orWhere($searchColumns[0], $searchTerm);
                } else {
                    $query = $query->orWhere($searchColumns[0], 'LIKE', '%' . $searchTerm . '%');
                }

                for ($i = 1; $i < count($searchColumns); $i++) {
                    $query = $query->orWhere($searchColumns[$i], 'LIKE', '%' . $searchTerm . '%');
                }
                $query = $query->orderBy($sortField, $sortDirection);

                return StaffResource::collection($query->paginate(5));
            }

            return StaffResource::collection(
                Staff::query()
                    ->where('warehouse_branch_id', $staff->warehouse_branch_id)
                    ->orderBy($sortField, $sortDirection)
                    ->paginate(5)
            );
        }

        return new JsonResponse([
            'message' => 'Forbidden'
        ], 403);
    }

    public function show(string $id, Request $request): JsonResponse|StaffResource
    {
        $user = $request->user();

        $staff = Staff::query()->findOrFail($id);

        if ($user->can('manage-all-staff')) {
            return new StaffResource($staff);
        }

        if ($user->can('manage-branch-staff')) {
            $staffLogin = Staff::query()->where('user_id', $user->getKey())->firstOrFail();

            if ($staff->warehouse_branch_id !== $staffLogin->warehouse_branch_id) {
                return new JsonResource([
                    'message' => 'Staff doesn\'t work in your branch',
                ], 422);
            }

            return new StaffResource($staff);
        }

        return new JsonResponse([
            'message' => 'Forbidden'
        ], 403);
    }

    public function store(CreateStaff $request): JsonResponse
    {
        $user = $request->user();

        if ($user->canAny(['manage-all-staff', 'manage-branch-staff'])) {
            try {
                Staff::query()->create($request->validated());
            } catch (Exception $exception) {
                return new JsonResponse([
                    'message' => $exception->getMessage(),
                ], 422);
            }

            return new JsonResponse(['message' => 'Staff successfully created.']);
        }

        return new JsonResponse(['message' => 'Staff successfully created.']);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $staff = Staff::query()->findOrFail($id);

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
                'exists:positions,id'
            ],
            'warehouse_branch_id' => [
                'required',
                'exists:warehouse_branches,id'
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

        $user = $request->user();

        if ($user->canAny(['manage-all-staff', 'manage-branch-staff'])) {
            if (
                $user->can('manage-branch-staff')
                && $staff->warehosue_branch_id !== $request->input('warehouse_branch_id')
            ) {
                return new JsonResponse([
                    'message' => 'Forbidden'
                ], 403);
            }

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

        return new JsonResponse([
            'message' => 'Forbidden'
        ], 403);
    }

    public function destroy(string $id, Request $request): JsonResponse
    {
        $staff = Staff::query()->findOrFail($id);

        $user = $request->user();

        if ($user->canAny(['manage-all-staff', 'manage-branch-staff'])) {
            $staffInformation = Staff::query()->where('user_id', $user->getkey())->firstOrFail();

            if ($staffInformation->getKey() == $id) {
                return new JsonResponse([
                    'message' => 'Could not delete your staff information.'
                ], 422);
            }

            if (
                $user->can('manage-branch-staff')
                && $staff->warehosue_branch_id !== $request->input('warehouse_branch_id')
            ) {
                return new JsonResponse([
                    'message' => 'Forbidden'
                ], 403);
            }

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

        return new JsonResponse([
            'message' => 'Forbidden'
        ], 403);
    }
}