<?php
namespace App\Repository;

use App\Entity\Comment;
use Doctrine\ORM\EntityRepository;

class CommentRepository extends EntityRepository
{
    /**
     * Find comments by article
     */
    public function findByArticle(int $articleId): ?array
    {
        return $this->createQueryBuilder('c')
            ->where('c.article = :articleId')
            ->setParameter('articleId', $articleId)
            ->orderBy('c.created_at', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find comments by author
     */
    public function findByAuthor(int $authorId): ?array
    {
        return $this->createQueryBuilder('c')
            ->where('c.author = :authorId')
            ->setParameter('authorId', $authorId)
            ->orderBy('c.created_at', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
