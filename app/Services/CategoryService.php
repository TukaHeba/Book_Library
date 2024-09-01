<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CategoryService
{
    /**
     * Retrieve all categories with pagination.
     *
     * @return LengthAwarePaginator
     */
    public function getAllCategories(): LengthAwarePaginator
    {
        try {
            return Category::paginate(5);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve categories: ' . $e->getMessage());
            throw new \Exception('Failed to retrieve categories');
        }
    }

    /**
     * Create a new category.
     *
     * @param array $data
     * @return Category|null
     */
    public function createCategory(array $data): ?Category
    {
        try {
            return Category::create($data);
        } catch (\Exception $e) {
            Log::error('Category creation failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Retrieve a specific category by ID.
     *
     * @param mixed $id
     * @return Category
     * @throws \Exception
     */
    public function showCategory($id): Category
    {
        try {
            return Category::findOrFail($id);
        } catch (\Exception $e) {
            Log::error('Category retrieval failed: ' . $e->getMessage());
            throw new \Exception('Category not found');
        }
    }

    /**
     * Update the specified category with new data.
     *
     * @param mixed $id
     * @param array $data
     * @return Category
     * @throws \Exception
     */
    public function updateCategory($id, array $data): Category
    {
        try {
            $category = Category::findOrFail($id);
            $category->update($data);

            return $category;
        } catch (\Exception $e) {
            Log::error('Category update failed: ' . $e->getMessage());
            throw new \Exception('Failed to update category');
        }
    }

    /**
     * Delete the specified category.
     *
     * @param mixed $id
     * @return bool
     * @throws \Exception
     */
    public function deleteCategory($id): bool
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();

            return true;
        } catch (\Exception $e) {
            Log::error('Category deletion failed: ' . $e->getMessage());
            throw new \Exception('Failed to delete category');
        }
    }
}
