<?php
namespace App\Service;

use App\Entity\Article;
use App\Entity\User;
use App\Entity\Category;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;

class ArticleService  {

    private EntityManagerInterface $em;
    private ArticleRepository $articleRepository;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
        $this->articleRepository = $this->em->getRepository(Article::class);
    }

    /**
     * Create a new article
     */
    public function safeCreateArticle(string $title , string $content, 
                                User $author , array $categories = []) : ?Article {

        $this->em->beginTransaction();
        try {
            $article = new Article();
            $article->setTitle($title);
            $article->setContent($content);
            $article->setAuthor($author);
        
            foreach($categories as $category) {
                $article->addCategory($category);
            }

            $this->em->persist($article);
            $this->em->flush();
            $this->em->commit();
            return $article;
        }
        catch(\Throwable $e) {
            $this->em->rollback();
            return null;
        }
        

    }

    /**
     * Update an existing article
     */
    public function safeUpdateArticle(Article $article, array $data ,
                                array $categories = null) : ?Article {

        $this->em->beginTransaction();
        try {
            if(isset($data['title'])) {
                $article->setTitle($data['title']);
            }

            if(isset($data['content'])) {
                $article->setContent($data['content']);
            }

            if ($categories !== null) {
                $article->getCategories()->clear(); 
                foreach ($categories as $category) {
                    if ($category instanceof Category) {
                        $article->addCategory($category);
                    }
                }
            }

            $this->em->flush();
            $this->em->commit();
            return $article;
        }
        catch(\Throwable $e) {
            $this->em->rollback();
            return null;
        }
        
        
    }

    /**
     * Delete an article
     */
    public function safeDeleteArticle(Article $article): bool {
        $this->em->beginTransaction();
        try {
            $this->em->remove($article);
            $this->em->flush();
            $this->em->commit();
            return true;
        } catch (\Throwable $e) {
            $this->em->rollback();
            return false;
        }
    }

    public function getAllArticles() : array {

        return $this->articleRepository->findAll();

    }

    public function getArticlesByCategory(string $categoryName) : array {

        return $this->articleRepository->findByCategory($categoryName);

    }

    public function getArticlesByAuthor(User $author): ?array
    {
        return $this->articleRepository->
                    findByAuthor($author->getId());
    }

    public function getArticlesByKeyword(string $keyword) : ?array
    {
        return $this->articleRepository->
                    findByKeyword($keyword);
    }

}