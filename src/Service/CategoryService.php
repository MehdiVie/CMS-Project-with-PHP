<?php
namespace App\Service;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;

class CategoryService
{
    private EntityManagerInterface $em;
    private CategoryRepository $categoryRepository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        // Repository 
        $this->categoryRepository = $em->getRepository(Category::class);
    }

    /**
     * Create new category
     */
    public function createCategory(string $categoryName): Category
    {
        $category = new Category();
        $category->setCategoryName($categoryName);

        $this->em->persist($category);
        $this->em->flush();

        return $category;
    }

    /**
     * Get category by name
     */
    public function getCategoryByName(string $categoryName): ?Category
    {
        return $this->categoryRepository->findOneByName($categoryName);
    }

    /**
     * Update category name
     */
    public function updateCategory(Category $category, string $newName): Category
    {
        $category->setCategoryName($newName);
        $this->em->flush();

        return $category;
    }

    /**
     * Delete category
     */
    public function deleteCategory(Category $category): void
    {
        $this->em->remove($category);
        $this->em->flush();
    }

    /**
     * Get all categories
     */
    public function getAllCategories(): array
    {
        return $this->categoryRepository->findAllOrdered();
    }
}
