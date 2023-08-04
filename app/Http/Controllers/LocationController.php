<?php

namespace App\Http\Controllers;

use App\Http\Requests\Location\CreateLocation;
use App\Http\Resources\LocationResource;
use App\Models\Location;
use App\Models\Staff;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class LocationController extends Controller
{
    public function index(Request $request): JsonResponse|AnonymousResourceCollection
    {
        $user = $request->user();

        $request->validate([
            'no_pagination' => ['nullable', 'boolean'],
        ]);

        if ($user->canAny(['manage-branch-location', 'read-location'])) {
            $staff = Staff::query()->where('user_id', $user->getKey())->firstOrFail();
            $query = Location::query()
                ->where('warehouse_branch_id', $staff->warehouse_branch_id);

            if ($request->input('no_pagination')) {
                return LocationResource::collection($query->get());
            }

            return LocationResource::collection($query->paginate(5));
        }

        if ($user->can('manage-location')) {
            $query = Location::query();

            if ($request->input('no_pagination')) {
                return LocationResource::collection($query->get());
            }

            return LocationResource::collection($query->paginate(5));
        }

        return new JsonResponse(['message' => 'Forbidden'], 403);
    }

    public function store(CreateLocation $request): JsonResponse
    {
        $user = $request->user();

        if ($user->can('manage-branch-location')) {
            $staff = Staff::query()->where('user_id', $user->getKey())->firstOrFail();

            if ($staff->warehouse_branch_id !== $request->input('warehouse_branch_id')) {
                return new JsonResponse(['message' => 'You do have access to do this action.'], 403);
            }

            Location::query()->create($request->validated());

            return new JsonResponse(['message' => 'Location created successfully.']);
        }

        if ($user->can('manage-location')) {
            Location::query()->create($request->validated());

            return new JsonResponse(['message' => 'Location created successfully.']);
        }

        return new JsonResponse(['message' => 'Forbidden'], 403);
    }

    public function show(string $id, Request $request): JsonResponse
    {
        $user = $request->user();
        $location = Location::query()->findOrFail($id);

        if ($user->canAny(['manage-branch-location', 'read-location'])) {
            $staff = Staff::query()->where('user_id', $user->getKey())->firstOrFail();

            if ($staff->warehouse_branch_id !== $location->warehouse_branch_id) {
                return new JsonResponse(['message' => 'You do have access to do this action.'], 403);
            }

            return new JsonResponse($location);
        }

        if ($user->can('manage-location')) {
            return new JsonResponse($location);
        }

        return new JsonResponse(['message' => 'Forbidden'], 403);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $user = $request->user();
        $location = Location::query()->findOrFail($id);

        $validated = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:50'
            ],
            'warehouse_branch_id' => [
                'required',
                'exists:warehoure_branches,id',
                Rule::unique('locations', 'warehouse_branch_id')
                    ->where('user_id', $this->input('name'))
                    ->ignore($location),
            ],
            'description' => ['required', 'max:255'],
        ])->validated();

        if ($user->can('manage-branch-location')) {
            $staff = Staff::query()->where('user_id', $user->getKey())->firstOrFail();

            if ($staff->warehouse_branch_id !== $request->input('warehouse_branch_id')) {
                return new JsonResponse(['message' => 'You do have access to do this action.'], 403);
            }

            if (!$location->update($validated)) {
                return new JsonResponse(['message' => 'Updating location failed.'], 422);
            }
            return new JsonResponse(['message' => 'Location updated successfully.']);
        }

        if ($user->can('manage-location')) {
            if (!$location->update($validated)) {
                return new JsonResponse(['message' => 'Updating location failed.'], 422);
            }
            return new JsonResponse(['message' => 'Location updated successfully.']);
        }

        return new JsonResponse(['message' => 'Forbidden'], 403);
    }

    public function destroy(string $id, Request $request): JsonResponse
    {
        $user = $request->user();
        $location = Location::query()->findOrFail($id);

        if ($user->can('manage-branch-location')) {
            $staff = Staff::query()->where('user_id', $user->getKey())->firstOrFail();

            if ($staff->warehouse_branch_id !== $request->input('warehouse_branch_id')) {
                return new JsonResponse(['message' => 'You do have access to do this action.'], 403);
            }

            if ($location->stocks->count() > 0) {
                return new JsonResponse(['message' => 'Location is storing stock. Could not delete this location.'], 405);
            }

            if (!$location->delete()) {
                return new JsonResponse(['message' => 'Deleting location failed.'], 422);
            }

            return new JsonResponse(['message' => 'Location deleted successfully.']);
        }

        if ($user->can('manage-location')) {
            if ($location->stocks->count() > 0) {
                return new JsonResponse(['message' => 'Location is storing stock. Could not delete this location.'], 405);
            }

            if (!$location->delete()) {
                return new JsonResponse(['message' => 'Deleting location failed.'], 422);
            }

            return new JsonResponse(['message' => 'Location deleted successfully.']);
        }

        return new JsonResponse(['message' => 'Forbidden'], 403);
    }
}