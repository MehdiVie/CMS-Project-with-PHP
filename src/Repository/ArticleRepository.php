<?php
namespace App\Repository;

use App\Entity\Article;
use Doctrine\ORM\EntityRepository;

class ArticleRepository extends EntityRepository {

    /**
     * Find recent articles by creation date
     */
    public function findRecentArticles(int $limit=5) : array {
        return $this->createQueryBuilder('a')
               ->orderBy('a.createdAt' , 'DESC')
               ->setMaxResults($limit)
               ->getQuery()
               ->getResult();
    }

    /**
     * Find articles by category
     */
    public function findByCategory(string $categoryName) : array {

        return $this->createQueryBuilder('a')
               ->join('a.categories' , 'c')
               ->where('c.categoryName = :name' )
               ->setParameter('name' , $categoryName)
               ->getQuery()
               ->getResult();
    }

    /**
     * Find article by ID
     */
    public function findById(int $id) : ?Article {
        return $this->find($id);
    }


    /**
     * Find articles of author id
     */
    public function findByAuthor(int $authorId): array
    {
        return $this->createQueryBuilder('a')
            ->where('a.author = :authorId')
            ->setParameter('authorId', $authorId)
            ->getQuery()
            ->getResult();
    }

    public function findByKeyword(string $keyword) : ?array
    {
        return $this->createQueryBuilder('a')
            ->where('a.title like :keyword OR a.content like :keyword')
            ->setParameter('keyword', '%'.$keyword.'%')
            ->getQuery()
            ->getResult();
    }

}