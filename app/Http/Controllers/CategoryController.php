<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\CreateCategory;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection|Collection
    {
        $user = $request->user();
        if ($user->canAny(['manage-category', 'read-category'])) {
            $request->validate([
                'no_pagination' => ['nullable', 'boolean'],
            ]);

            if ($request->input('no_pagination')) {
                return Category::query()->select('id', 'name', 'unit')->get();
            }

            $sortField = $request->input('sort_field', 'id');
            if (!in_array($sortField, ['id', 'name', 'unit', 'created_at', 'updated_at'])) {
                $sortField = 'id';
            }

            $sortDirection = $request->input('sort_direction', 'asc');
            if (!in_array($sortDirection, ['desc', 'asc'])) {
                $sortDirection = 'asc';
            }

            $searchColumns = $request->input('search_columns', ['id', 'name'], 'unit');

            if ($request->input('no_pagination')) {
                return CategoryResource::collection(Category::query()->get());
            }

            if ($searchTerm = $request->input('search')) {

                $query = Category::query();

                if ($searchColumns[0] === 'id') {
                    $query = $query->where($searchColumns[0], $searchTerm);
                } else {
                    $query = $query->where($searchColumns[0], 'LIKE', '%' . $searchTerm . '%');
                }

                for ($i = 1; $i < count($searchColumns); $i++) {
                    $query = $query->orWhere($searchColumns[$i], 'LIKE', '%' . $searchTerm . '%');
                }
                $query = $query->orderBy($sortField, $sortDirection);

                return CategoryResource::collection($query->paginate(5));
            }


            return CategoryResource::collection(Category::query()->orderBy($sortField, $sortDirection)->paginate(5));
        }

        return new JsonResponse(['message' => 'Forbidden'], 403);
    }

    public function show(string $id, Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->can('manage-category')) {
            $category = Category::query()->findOrFail($id);

            return new JsonResponse(new CategoryResource($category));
        }

        return new JsonResponse(['message' => 'Forbidden'], 403);
    }

    public function store(CreateCategory $request): JsonResponse
    {
        $user = $request->user();

        if ($user->can('manage-category')) {
            try {
                Category::query()->create($request->validated());
            } catch (Exception $exception) {
                return new JsonResponse([
                    'message' => $exception->getMessage(),
                ], 422);
            }

            return new JsonResponse([
                'message' => 'Create category successfully'
            ]);
        }

        return new JsonResponse(['message' => 'Forbidden'], 403);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $user = $request->user();

        if ($user->can('manage-category')) {
            $category = Category::query()->findOrFail($id);

            $validated = Validator::make($request->all(), [
                'name' => [
                    'required',
                    'string',
                    'max:50',
                    Rule::unique('categories', 'name')->ignore($category),
                ],
                'description' => [
                    'required',
                    'string',
                    'max:255'
                ],
                'unit' => [
                    'required',
                    'string',
                    'max:20'
                ],
            ])->validate();

            try {
                if (!$category->update($validated)) {
                    return new JsonResponse([
                        'message' => 'Update Category failed',
                    ], 422);
                }
            } catch (Exception $exception) {
                return new JsonResponse([
                    'message' => $exception->getMessage(),
                ], 422);
            }

            return new JsonResponse(['message' => 'Category successfully updated.']);
        }

        return new JsonResponse(['message' => 'Forbidden'], 403);
    }

    public function destroy(string $id, Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->can('manage-category')) {
            $category = Category::query()->findOrFail($id);

            if ($category->imports->count() > 0) {
                return new JsonResponse([
                    'message' => 'Could not delete category, created import',
                ], 422);
            }

            if ($category->exports->count() > 0) {
                return new JsonResponse([
                    'message' => 'Could not delete category, created export',
                ], 422);
            }

            try {
                if (!$category->delete()) {
                    return new JsonResponse([
                        'message' => 'Delete category failed',
                    ], 422);
                }
            } catch (Exception $exception) {
                return new JsonResponse([
                    'message' => $exception->getMessage(),
                ], 422);
            }

            return new JsonResponse(['message' => 'Category successfully deleted.']);
        }

        return new JsonResponse(['message' => 'Forbidden'], 403);
    }
}
