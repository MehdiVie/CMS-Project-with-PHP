<?php
namespace App\Controller;

use App\Service\ArticleService;
use App\Service\CategoryService;
use App\Service\UserService;
use App\Entity\Article;
use App\Entity\User;
use App\Entity\Category;

class ArticleController
{
    private ArticleService $articleService;
    private CategoryService $categoryService;
    private UserService $userService;

    public function __construct(
        ArticleService $articleService,
        CategoryService $categoryService,
        UserService $userService
    ) {
        $this->articleService = $articleService;
        $this->categoryService = $categoryService;
        $this->userService = $userService;
    }

    public function createArticle(string $title, string $content, User $author, array $categories): Article
    {
        $article = $this->articleService->safeCreateArticle($title, $content, $author, $categories);
        if (!$article) throw new \RuntimeException("Failed to create article");
        echo "Article {$article->getTitle()} created.\n";
        return $article;
    }

    public function listAllArticles(): void
    {
        $articles = $this->articleService->getAllArticles();
        foreach ($articles as $a) {
            echo "- {$a->getTitle()} by {$a->getAuthor()->getName()}\n";
        }
    }

    // src/Controller/ArticleController.php
    public function updateArticle(Article $article, array $data, array $categories = null): ?Article
    {
        return $this->articleService->safeUpdateArticle($article, $data, $categories);
    }


    public function deleteArticle(Article $article): bool
    {
        $success = $this->articleService->safeDeleteArticle($article);
        echo $success ? "Article deleted successfully.\n" : "Failed to delete article.\n";
        return $success;
    }
}
