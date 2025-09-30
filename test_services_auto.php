<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/bootstrap.php'; // 

use App\Service\UserService;
use App\Service\CategoryService;
use App\Service\ArticleService;

// 
$userService = new UserService($entityManager);
$categoryService = new CategoryService($entityManager);
$articleService = new ArticleService($entityManager);

echo "=== AUTO SAFE CRUD TEST START ===\n\n";


//  Users

echo "1️ Users\n";

//
$userEmail = 'auto_test@example.com';
$user = $userService->getUserByEmail($userEmail) 
        ?? $userService->safeCreateUser('AutoTestUser', $userEmail, 'pass123');
echo "User: {$user->getName()} ({$user->getEmail()})\n";

// Categories
echo "\n2️⃣ Categories\n";

$categoryNames = ['Tech', 'Sport', 'Animal'];
$categories = [];

foreach ($categoryNames as $catName) {
    $cat = $categoryService->getCategoryByName($catName) 
        ?? $categoryService->safeCreateCategory($catName);
    $categories[$catName] = $cat;
    echo "Category: {$cat->getCategoryName()}\n";
}


// Articles
echo "\n3️⃣ Articles\n";

// 
$articleTitle = 'Auto Test Article';
$articleContent = 'This is an auto-generated test article.';

//
$allArticles = $articleService->getAllArticles();
$article = null;
foreach ($allArticles as $a) {
    if ($a->getTitle() === $articleTitle) {
        $article = $a;
        break;
    }
}

if (!$article) {
    $article = $articleService->safeCreateArticle(
        $articleTitle,
        $articleContent,
        $user,
        [$categories['Tech'], $categories['Sport']]
    );
    echo "Article Created: {$article->getTitle()} (Categories: ".implode(', ', array_map(fn($c)=>$c->getCategoryName(), $article->getCategories()->toArray())).")\n";
} else {
    echo "Article already exists: {$article->getTitle()}\n";
}


// Update Article

echo "\n4️⃣ Update Article\n";


$updatedArticle = $articleService->safeUpdateArticle($article, [
    'title' => 'Updated Auto Article',
    'content' => 'Updated auto-generated content.'
]);
echo "Updated Article: {$updatedArticle->getTitle()}, Categories: ".implode(', ', array_map(fn($c)=>$c->getCategoryName(), $updatedArticle->getCategories()->toArray()))."\n";

//
$newCategories = [$categories['Sport'], $categories['Animal']];
$updatedArticle2 = $articleService->safeUpdateArticle($article, ['title' => 'Updated Auto Article 2'], $newCategories);
echo "Updated Article2: {$updatedArticle2->getTitle()}, Categories: ".implode(', ', array_map(fn($c)=>$c->getCategoryName(), $updatedArticle2->getCategories()->toArray()))."\n";


// Cleanup (Optional)

echo "\n Cleanup\n";

$articleService->safeDeleteArticle($updatedArticle2);
foreach ($categories as $cat) {
    $categoryService->safeDeleteCategory($cat);
}
$userService->safeDeleteUser($user);

echo "\n=== AUTO SAFE CRUD TEST END ===\n";
