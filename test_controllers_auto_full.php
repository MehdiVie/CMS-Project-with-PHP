<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/bootstrap.php';

use App\Service\UserService;
use App\Service\CategoryService;
use App\Service\ArticleService;
use App\Service\CommentService;

use App\Controller\UserController;
use App\Controller\CategoryController;
use App\Controller\ArticleController;
use App\Controller\CommentController;

// Services
$userService = new UserService($entityManager);
$categoryService = new CategoryService($entityManager);
$articleService = new ArticleService($entityManager);
$commentService = new CommentService($entityManager);

// Controllers
$userController = new UserController($userService);
$categoryController = new CategoryController($categoryService);
$articleController = new ArticleController($articleService, $categoryService, $userService);
$commentController = new CommentController($commentService, $articleService, $userService);

echo "=== AUTO CONTROLLER FULL CRUD TEST START ===\n";

// --- Users ---
echo "\n--- Users ---\n";
$users = [];
$users[] = $userController->createUser('CtrlUser1', 'ctrl_user1@example.com', 'pass123');
$users[] = $userController->createUser('CtrlUser2', 'ctrl_user2@example.com', 'pass123');
$userController->listAllUsers();

// Update a user
$updatedUser = $userController->updateUser($users[0], 'CtrlUser1Updated', 'newpass123');
echo "Updated User: {$updatedUser->getName()}\n";

// --- Categories ---
echo "\n--- Categories ---\n";
$categories = [];
$categories[] = $categoryController->createCategory('Tech');
$categories[] = $categoryController->createCategory('Sports');
$categoryController->listAllCategories();

// Update a category
$updatedCategory = $categoryController->updateCategory($categories[0], 'TechUpdated');
echo "Updated Category: {$updatedCategory->getCategoryName()}\n";

// --- Articles ---
echo "\n--- Articles ---\n";
$articles = [];
$articles[] = $articleController->createArticle(
    'Tech Article',
    'Content about tech.',
    $users[0],
    [$categories[0], $categories[1]]
);
$articles[] = $articleController->createArticle(
    'Sports Article',
    'Content about sports.',
    $users[1],
    [$categories[1]]
);
$articleController->listAllArticles();

// Update an article
$updatedArticle = $articleController->updateArticle(
    $articles[0],
    ['title' => 'Tech Article Updated', 'content' => 'Updated tech content.'],
    [$categories[0]]
);
echo "Updated Article: {$updatedArticle->getTitle()}\n";

// --- Comments ---
echo "\n--- Comments ---\n";
$comments = [];
$comments[] = $commentController->createComment(
    "Comment 1 on {$articles[0]->getTitle()}",
    $users[1],
    $articles[0]
);
$comments[] = $commentController->createComment(
    "Comment 2 on {$articles[0]->getTitle()}",
    $users[0],
    $articles[0]
);
foreach ($articles as $article) {
    $commentController->listCommentsByArticle($article);
}

// Update a comment
$updatedComment = $commentController->updateComment(
    $comments[0],
    "Updated comment on {$articles[0]->getTitle()}"
);
echo "Updated Comment: {$updatedComment->getContent()}\n";

// --- Cleanup ---
echo "\n--- Cleanup ---\n";

// Delete comments
foreach ($comments as $comment) {
    $commentController->deleteComment($comment);
}

// Delete articles
foreach ($articles as $article) {
    $articleController->deleteArticle($article);
}

// Delete categories
foreach ($categories as $cat) {
    $categoryController->deleteCategory($cat);
}

// Delete users
foreach ($users as $user) {
    $userController->deleteUser($user);
}

echo "\n=== AUTO CONTROLLER FULL CRUD TEST END ===\n";
