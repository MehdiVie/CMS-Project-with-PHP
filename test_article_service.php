<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/bootstrap.php'; // $entityManager is created here

use App\Service\UserService;
use App\Service\CategoryService;
use App\Service\ArticleService;

// ------------------------------
// STEP 1: Prepare Services
// ------------------------------
$userService = new UserService($entityManager);
$categoryService = new CategoryService($entityManager);
$articleService = new ArticleService($entityManager);

// ------------------------------
// STEP 2: Ensure we have an author (User object)
// ------------------------------
$authorEmail = 'alice@example.com';

// Try to find existing user
$author = $userService->getUserByEmail($authorEmail);

if (!$author) {
    // If not found, create one
    $author = $userService->createUser('Alice', $authorEmail, 'secret123');
    echo "✅ User created with ID: " . $author->getId() . PHP_EOL;
} else {
    echo "🔎 User found: " . $author->getName() . " (" . $author->getEmail() . ")" . PHP_EOL;
}

// ------------------------------
// STEP 3: Create Categories
// ------------------------------
$techCategory = $categoryService->createCategory('Tech');
$sportsCategory = $categoryService->createCategory('Sports');

echo "✅ Categories created: Tech, Sports" . PHP_EOL;

// ------------------------------
// STEP 4: Create Article
// ------------------------------
$article = $articleService->createArticle(
    'My First Article',
    'This is the content of my first article.',
    $author, // Pass a User object, NOT an array
    [$techCategory, $sportsCategory] // Optional: pass categories
);

echo "📝 Article created with ID: " . $article->getId() . PHP_EOL;

// ------------------------------
// STEP 5: Show linked categories for the article
// ------------------------------
$categories = $article->getCategories();

echo "📂 Article belongs to categories: ";
foreach ($categories as $cat) {
    echo $cat->getCategoryName() . " ";
}
echo PHP_EOL;
