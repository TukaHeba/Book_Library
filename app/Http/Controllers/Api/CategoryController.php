<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Services\CategoryService;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Services\ApiResponseService;

class CategoryController extends Controller
{
    /**
     * The category service instance.
     *
     * @var CategoryService
     */
    protected $categoryService;

    /**
     * CategoryController constructor.
     *
     * @param CategoryService $categoryService
     */
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
        $this->middleware('role:admin');
    }

    /**
     * Display a listing of categories.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $categories = $this->categoryService->getAllCategories();
            return ApiResponseService::paginated($categories, CategoryResource::class, 'Categories retrieved successfully', 200);
        } catch (\Exception $e) {
            return ApiResponseService::error($e->getMessage(), 400);
        }
    }

    /**
     * Store a newly created category.
     *
     * @param StoreCategoryRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreCategoryRequest $request)
    {
        $validated = $request->validated();
        $category = $this->categoryService->createCategory($validated);

        if ($category) {
            return ApiResponseService::success(new CategoryResource($category), 'Category created successfully', 201);
        } else {
            return ApiResponseService::error('Category creation failed', 400);
        }
    }

    /**
     * Display the specified category.
     *
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $category = $this->categoryService->showCategory($id);
            return ApiResponseService::success(new CategoryResource($category), 'Category retrieved successfully', 200);
        } catch (\Exception $e) {
            return ApiResponseService::error($e->getMessage(), 404);
        }
    }

    /**
     * Update the specified category.
     *
     * @param UpdateCategoryRequest $request
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateCategoryRequest $request, $id)
    {
        try {
            $validated = $request->validated();
            $category = $this->categoryService->updateCategory($id, $validated);

            return ApiResponseService::success(new CategoryResource($category), 'Category updated successfully', 200);
        } catch (\Exception $e) {
            return ApiResponseService::error($e->getMessage(), 400);
        }
    }

    /**
     * Remove the specified category.
     *
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $this->categoryService->deleteCategory($id);
            return ApiResponseService::success(null, 'Category deleted successfully', 200);
        } catch (\Exception $e) {
            return ApiResponseService::error($e->getMessage(), 400);
        }
    }
}
