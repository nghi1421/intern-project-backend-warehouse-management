<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\CreateCategory;
use App\Models\Category;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        return new JsonResponse([
            'categories' => Category::query()->get(),
            'pagination' => Category::query()->paginate(5)
        ]);
    }

    public function store(CreateCategory $request): JsonResponse
    {
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

    public function update(Request $request, Category $category)
    {
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

    public function destroy(Category $category)
    {
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
}
