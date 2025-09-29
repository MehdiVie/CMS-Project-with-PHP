<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/bootstrap.php';  //entityManager 

use App\Service\UserService;

// $entityManager 
$userService = new UserService($entityManager);

// 1. Create new user
$newUser = $userService->createUser("Alice", "alice@example.com", "secret123");
echo "✅ User created with ID: " . $newUser->getId() . PHP_EOL;

// 2. Find user by email
$foundUser = $userService->getUserByEmail("alice@example.com");
if ($foundUser) {
    echo "🔎 Found user: " . $foundUser->getName() . " (" . $foundUser->getEmail() . ")" . PHP_EOL;
}

// 3. Get all active users
$activeUsers = $userService->getAllActiveUsers();
echo "👥 Active Users Count: " . count($activeUsers) . PHP_EOL;

// 4. Deactivate the user
$userService->deactivateUser($foundUser);
echo "🚫 User deactivated: " . $foundUser->getEmail() . PHP_EOL;
