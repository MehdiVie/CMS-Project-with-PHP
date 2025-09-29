<?php
namespace App\Service;

use App\Entity\Article;
use App\Entity\User;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;

class ArticleService  {

    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;

    }

    /**
     * Create a new article
     */
    public function createArticle(string $title , string $content, 
                                User $author , array $categories = []) : Article {

        $article = new Article();
        $article->setTitle($title);
        $article->setContent($content);
        $article->setAuthor($author);
        
        foreach($categories as $category) {
            $article->addCategory($category);
        }

        $this->em->persist($article);
        $this->em->flush();

        return $article;

    }

    /**
     * Update an existing article
     */
    public function updateArticle(Article $article, array $data ,
                                array $categories = []) : Article {
        if(isset($data['title'])) {
            $article->setTitle($data['title']);
        }

        if(isset($data['content'])) {
            $article->setContent($data['content']);
        }

        foreach($article->getCategories() as $cat) {
            $article->removeCategory($cat);
        }

        foreach($categories as $category) {
            $article->addCategory($category);
        }

        $this->em->flush();

        return $article;
    }

    /**
     * Delete an article
     */
    public function deleteArticle(Article $article): void
    {
        $this->em->remove($article);
        $this->em->flush();
    }

    public function getAllArticles() : array {

        return $this->em->getRepository(Article::class)->findAll();

    }

    public function getArticlesByCategory(string $categoryName) : array {

        return $this->em->getRepository(Article::class)->findByCategory($categoryName);

    }

    public function getArticlesByAuthor(User $author): ?array
    {
        return $this->em->getRepository(Article::class)->
                    findByAuthor($author->getId());
    }

}