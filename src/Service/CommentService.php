<?php
namespace App\Service;

use App\Entity\Comment;
use App\Entity\User;
use App\Entity\Article;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;

class CommentService
{
    private EntityManagerInterface $em;
    private commentRepository $commentRepository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        // Repository 
        $this->commentRepository = $em->getRepository(Comment::class);
    }

    /**
     * Create new Comment
     */
    public function safeCreateComment(string $content , User $author ,
                                      Article $article): ?Comment
    {
        $this->em->beginTransaction();
        try {
            $comment = new Comment();
            $comment->setContent($content);
            $comment->setAuthor($author);
            $comment->setArticle($article);

            $this->em->persist($comment);
            $this->em->flush();
            $this->em->commit();
            return $comment;

        } catch (\Throwable $e) {
            $this->em->rollback();
            return null;
        }
        
    }

    /**
     * Update Comment name
     */
    public function safeUpdateComment(Comment $comment , 
                                     string $content): ?Comment
    {
        $this->em->beginTransaction();
        try {
            $comment->setContent($content);
            
            $this->em->flush();
            $this->em->commit();
            return $comment;

        }
        catch(\Throwable $e) {

            $this->em->rollback();
            return null;
        }


        
    }

    /**
     * Delete Comment
     */
    public function safeDeleteComment(Comment $comment): bool
    {
        $this->em->beginTransaction();
        try {
            $this->em->remove($comment);

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
     * Get Comments by article
     */
    public function getCommentsByArticle(Article $article): ?array
    {
        return $this->commentRepository->findByArticle($article->getId());
    }

    /**
     * Get Comments by author
     */
    public function getCommentsByAuthor(User $author): ?array
    {
        return $this->commentRepository->findByAuthor($author->getId());
    }

    public function getAllComments(): array
    {
        return $this->commentRepository->findAll();
    }
}
