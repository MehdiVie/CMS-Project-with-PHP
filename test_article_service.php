<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/bootstrap.php'; // $entityManager is created here

use App\Service\UserService;
use App\Service\CategoryService;
use App\Service\ArticleService;


// Prepare Services

$userService = new UserService($entityManager);
$categoryService = new CategoryService($entityManager);
$articleService = new ArticleService($entityManager);


// Ensure we have an author (User object)

$authorEmail = 'mehdi@example.com';

// Try to find existing user
$author = $userService->getUserByEmail($authorEmail);

if (!$author) {
    // If not found, create one
    $author = $userService->createUser('Mehdi', $authorEmail, 'secret123');
    echo " User created with ID: " . $author->getId() . PHP_EOL;
} else {
    echo " User found: " . $author->getName() . " (" . $author->getEmail() . ")" . PHP_EOL;
}

// 

/*$techCategory = $categoryService->createCategory('Tech');
$sportsCategory = $categoryService->createCategory('Sports');*/
$techCategory = $categoryService->getCategoryByName('Tech');
$animalCategory = $categoryService->createCategory('Animal');
//$Category = $categoryService->createCategory('Now');

echo " Categories created" . PHP_EOL;

$article = $articleService->createArticle(
    'My best Article',
    'This is the content of my best article.',
    $author, // Pass a User object, NOT an array
    [$techCategory, $animalCategory] // Optional: pass categories
);

echo " Article created with ID: " . $article->getId() . PHP_EOL;


// Show linked categories for the article
$categories = $article->getCategories();

echo " Article belongs to categories: ";
foreach ($categories as $cat) {
    echo $cat->getCategoryName() . " ";
}
echo PHP_EOL;

$allArticles = $articleService->getAllArticles();
echo " All Articles:\n";
foreach ($allArticles as $article) {
    echo "- {$article->getTitle()} by {$article->getAuthor()->getName()}\n";
}


$techArticles = $articleService->getArticlesByCategory("Tech");
echo " Tech Articles:\n";
foreach ($techArticles as $article) {
    echo "- {$article->getTitle()}\n";
}


$aliceArticles = $articleService->getArticlesByAuthor($author);
echo " Mehdi's Articles:\n";
foreach ($aliceArticles as $article) {
    echo "- {$article->getTitle()}\n";
}



