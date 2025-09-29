<?php
namespace App\Service;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;

class CategoryService
{
    private EntityManagerInterface $em;
    
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
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
        return $this->em->getRepository(Category::class)->findAll();
    }
}
