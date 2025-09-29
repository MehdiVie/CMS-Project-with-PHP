<?php
namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    /**
     * Find user by Email
     */
    public function findByEmail(string $email): ?User
    {
        return $this->findOneBy(['email' => $email]);
    }

    /**
     * Get all active users
     */
    public function findActiveUsers(): array
    {
        return $this->findBy(['isActive' => true]);
    }
}
