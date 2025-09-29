<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/bootstrap.php';  //entityManager 

use App\Service\UserService;

// $entityManager 
$userService = new UserService($entityManager);

// Create new user
$newUser = $userService->createUser("Mehdi", "mehdi@example.com", "secret123");
echo " User created with ID: " . $newUser->getId() . PHP_EOL;

// Find user by email
$foundUser = $userService->getUserByEmail("mehdi@example.com");
if ($foundUser) {
    echo " Found user: " . $foundUser->getName() . " (" . $foundUser->getEmail() . ")" . PHP_EOL;
}

// Get all active users
$activeUsers = $userService->getAllActiveUsers();
echo " Active Users Count: " . count($activeUsers) . PHP_EOL;

// Deactivate the user
$userService->deactivateUser($foundUser);
echo " User deactivated: " . $foundUser->getEmail() . PHP_EOL;
