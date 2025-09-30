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
    public function safeCreateCategory(string $categoryName): Category
    {
        $this->em->beginTransaction();
        try {
            $category = new Category();
            $category->setCategoryName($categoryName);

            $this->em->persist($category);
            $this->em->flush();
            $this->em->commit();
            return $category;

        } catch (\Throwable $e) {
            $this->em->rollback();
            return null;
        }
        
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
    public function safeUpdateCategory(Category $category, string $newName): Category
    {
        $this->em->beginTransaction();
        try {
            $category->setCategoryName($newName);
            
            $this->em->flush();
            $this->em->commit();
            return $category;

        }
        catch(\Throwable $e) {

            $this->em->rollback();
            return null;
        }


        
    }

    /**
     * Delete category
     */
    public function safeDeleteCategory(Category $category): bool
    {
        $this->em->beginTransaction();
        try {
            $this->em->remove($category);

            $this->em->flush();
            $this->em->commit();
            return true;
        }
        catch(\Throwable $e) {
            $this->em->rollback();
            return false;
        }
        

    }
    

    /**
     * Get all categories
     */
    public function getAllCategories(): array
    {
        return $this->categoryRepository->findAllOrdered();
    }
}
