<?php

namespace App\Http\Controllers;

use App\Http\Requests\Provider\CreateProvider;
use App\Http\Resources\ProviderResource;
use App\Models\Provider;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProviderController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $request->validate([
            'no_pagination' => ['nullable', 'boolean'],
        ]);

        if ($request->input('no_pagination')) {
            return ProviderResource::collection(Provider::query()->get());
        }

        return ProviderResource::collection(Provider::query()->paginate(5));
    }

    public function store(CreateProvider $request): JsonResponse
    {
        try {
            Provider::query()->create($request->validated());
        } catch (Exception $exception) {
            return new JsonResponse([
                'message' => $exception->getMessage(),
            ], 422);
        }

        return new JsonResponse([
            'message' => 'Provider created successfully'
        ]);
    }

    public function show(string $id)
    {
        $provider = Provider::query()->find($id);

        if (!$provider) {
            return new JsonResponse([
                'message' => 'Provider not found'
            ], 404);
        }

        return new JsonResponse([
            'message' => 'Provider updated successfully'
        ]);
    }

    public function update(Request $request, string $id)
    {
        $provider = Provider::query()->find($id);

        if (!$provider) {
            return new JsonResponse([
                'message' => 'Provider not found'
            ], 404);
        }

        $validated = Validator::make($request->all(), [
            'name' => [
                'required',
                'max:50',
                Rule::unique('providers', 'name')->ignore($provider),
            ],
            'email' => [
                'required',
                'max:255',
                Rule::unique('providers', 'email')->ignore($provider),
            ],
            'address' => ['required', 'max:255'],
            'phone_number' => [
                'required',
                'max:15',
                'regex:/^(0?)(3[2-9]|5[6|8|9]|7[0|6-9]|8[0-6|8|9]|9[0-4|6-9])[0-9]{7}$/',
                Rule::unique('providers', 'phone_number')->ignore($provider),
            ],
        ])->validate();

        try {
            if (!$provider->update($validated)) {
                return new JsonResponse([
                    'message' => 'Updating provider failed',
                ], 422);
            }
        } catch (Exception $exception) {
            return new JsonResponse([
                'message' => $exception->getMessage(),
            ], 422);
        }

        return new JsonResponse(['message' => 'Provider successfully updated.']);
    }

    public function destroy(string $id)
    {
        $provider = Provider::query()->find($id);

        if (!$provider) {
            return new JsonResponse([
                'message' => 'Provider not found.'
            ], 404);
        }

        if ($provider->imports->count() > 0) {
            return new JsonResponse([
                'message' => 'Could not delete provider in import',
            ], 422);
        }

        try {
            if (!$provider->delete()) {
                return new JsonResponse([
                    'message' => 'Delete provider failed',
                ], 422);
            }
        } catch (Exception $exception) {
            return new JsonResponse([
                'message' => $exception->getMessage(),
            ], 422);
        }

        return new JsonResponse(['message' => 'Provider successfully deleted.']);
    }
}
