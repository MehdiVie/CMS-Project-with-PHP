<?php
namespace App\Controller;

use App\Service\CategoryService;
use App\Entity\Category;

class CategoryController
{
    private CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function createCategory(string $name): Category
    {
        $category = $this->categoryService->safeCreateCategory($name);
        if (!$category) throw new \RuntimeException("Failed to create category");
        echo "Category created: {$category->getCategoryName()}\n";
        return $category;
    }

    public function listAllCategories(): array
    {
        return $this->categoryService->getAllCategories(); 
    }

    public function getCategoryByName(string $name): ?Category
    {
        $cat = $this->categoryService->getCategoryByName($name);
        if ($cat) echo "Category found: {$cat->getCategoryName()}\n";
        return $cat;
    }

    
    public function updateCategory(Category $category, string $newName): ?\App\Entity\Category
    {
        return $this->categoryService->safeUpdateCategory($category, $newName);
    }


    public function deleteCategory(Category $category): bool
    {
        $success = $this->categoryService->safeDeleteCategory($category);
        echo $success ? "Category deleted successfully.\n" : "Failed to delete category.\n";
        return $success;
    }
}
