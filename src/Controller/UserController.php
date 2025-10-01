<?php
namespace App\Controller;

use App\Service\UserService;
use App\Entity\User;

class UserController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function createUser(string $name, string $email, string $password): User
    {
        $user = $this->userService->safeCreateUser($name, $email, $password);
        if (!$user) throw new \RuntimeException("Failed to create user");
        echo "User created: {$user->getName()} ({$user->getEmail()})\n";
        return $user;
    }

    public function listAllUsers(): array
    {
        return $this->userService->getAllUsers();
    }

    public function getUserByEmail(string $email): ?User
    {
        $user = $this->userService->getUserByEmail($email);
        if ($user) echo "User found: {$user->getName()} ({$user->getEmail()})\n";
        return $user;
    }

    

    public function updateUser(User $user, string $newName, string $newPassword): ?User
    {
        return $this->userService->safeUpdateUser($user, [
            'name' => $newName,
            'password' => $newPassword
        ]);
}


    public function deleteUser(User $user): bool
    {
        $success = $this->userService->safeDeleteUser($user);
        echo $success ? "User deleted successfully.\n" : "Failed to delete user.\n";
        if ($success) {
            return true;
        } else {
            return false;
        }
        
    }
}
