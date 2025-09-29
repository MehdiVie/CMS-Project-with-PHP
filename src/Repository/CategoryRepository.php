<?php
namespace App\Repository;

use App\Entity\Category;
use Doctrine\ORM\EntityRepository;

class CategoryRepository extends EntityRepository
{
    /**
     * Find category by name
     */
    public function findOneByName(string $name): ?Category
    {
        return $this->createQueryBuilder('c')
            ->where('c.categoryName = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Get all categories ordered by name
     */
    public function findAllOrdered(): array
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.categoryName', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
