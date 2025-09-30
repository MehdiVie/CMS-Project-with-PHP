<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/bootstrap.php'; // 

use App\Service\UserService;
use App\Service\CategoryService;
use App\Service\ArticleService;
use App\Service\CommentService;

// 
$userService = new UserService($entityManager);
$categoryService = new CategoryService($entityManager);
$articleService = new ArticleService($entityManager);
$commentService = new CommentService($entityManager);

echo "=== AUTO SAFE CRUD TEST START ===\n\n";


//  Users

echo "\n Users\n";

//
$userEmail = 'auto_test@example.com';
$user = $userService->getUserByEmail($userEmail) 
        ?? $userService->safeCreateUser('AutoTestUser', $userEmail, 'pass123');
echo "User: {$user->getName()} ({$user->getEmail()})\n";

$userEmail2 = 'auto_test2@example.com';
$user2 = $userService->getUserByEmail($userEmail2) 
        ?? $userService->safeCreateUser('AutoTestUser2', $userEmail2, 'pass123');
echo "User2: {$user2->getName()} ({$user2->getEmail()})\n";

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

$articleTitle2 = '2Auto Test Article';
$articleContent2 = '2This is an auto-generated test article.';

$article2=null;
if (!$article2) {
    $article2 = $articleService->safeCreateArticle(
        $articleTitle2,
        $articleContent2,
        $user2,
        [$categories['Tech'], $categories['Sport']]
    );
    echo "Article2 Created: {$article2->getTitle()} (Categories: ".implode(', ', array_map(fn($c)=>$c->getCategoryName(), $article2->getCategories()->toArray())).")\n";
} else {
    echo "Article2 already exists: {$article2->getTitle()}\n";
}


// Update Article

echo "\n Update Article\n";


$updatedArticle = $articleService->safeUpdateArticle($article, [
    'title' => 'Updated Auto Article',
    'content' => 'Updated auto-generated content.'
]);
echo "Updated Article: {$updatedArticle->getTitle()}, Categories: ".implode(', ', array_map(fn($c)=>$c->getCategoryName(), $updatedArticle->getCategories()->toArray()))."\n";

//
$newCategories = [$categories['Sport'], $categories['Animal']];
$updatedArticle2 = $articleService->safeUpdateArticle($article, ['title' => 'Updated Auto Article 2'], $newCategories);
echo "Updated Article2: {$updatedArticle2->getTitle()}, Categories: ".implode(', ', array_map(fn($c)=>$c->getCategoryName(), $updatedArticle2->getCategories()->toArray()))."\n";

echo "\n Comments\n";


$comment = $commentService->safeCreateComment(
    "This is a test comment.",
    $user,
    $article2
);
echo "Comment Created: {$comment->getContent()} by {$comment->getAuthor()->getEmail()}\n";


$updatedComment = $commentService->safeUpdateComment(
    $comment,
    "This is an updated test comment."
);
echo "Comment Updated: {$updatedComment->getContent()}\n";


$commentsByArticle = $commentService->getCommentsByArticle($article2);
echo "Comments for Article '{$article2->getTitle()}':\n";
foreach ($commentsByArticle as $c) {
    echo "- {$c->getContent()} (by {$c->getAuthor()->getEmail()})\n";
}


$commentsByAuthor = $commentService->getCommentsByAuthor($user);
echo "Comments by {$user->getEmail()}:\n";
foreach ($commentsByAuthor as $c) {
    echo "- {$c->getContent()} (on article {$c->getArticle()->getTitle()})\n";
}


if ($commentService->safeDeleteComment($updatedComment)) {
    echo "Comment deleted successfully.\n";
}


// Cleanup (Optional)

echo "\n Cleanup\n";

//  Delete Comments
$allComments = $commentService->getAllComments(); 

foreach ($allComments as $c) {
    $commentService->safeDeleteComment($c);
}

//  Delete Articles
$allArticles = $articleService->getAllArticles();
foreach ($allArticles as $a) {
    $articleService->safeDeleteArticle($a);
}

//  Delete Categories
foreach ($categories as $cat) {
    $categoryService->safeDeleteCategory($cat);
}

//  Delete Users
$userService->safeDeleteUser($user);
$userService->safeDeleteUser($user2);

echo "\n=== AUTO SAFE CRUD TEST END ===\n";
echo "\n=== AUTO SAFE CRUD TEST END ===\n";
