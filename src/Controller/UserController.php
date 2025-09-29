<?php
namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManager;

class UserController
{
    private EntityManager $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    // Create
    public function create(string $username): User
    {
        $user = new User();
        $user->setUsername($username);

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    // Read all
    public function getAll(): array
    {
        return $this->em->getRepository(User::class)->findAll();
    }

    // Read by ID
    public function getById(int $id): ?User
    {
        return $this->em->getRepository(User::class)->find($id);
    }

    // Update
    public function update(int $id, string $username): ?User
    {
        $user = $this->getById($id);
        if (!$user) return null;

        $user->setUsername($username);
        $this->em->flush();

        return $user;
    }

    // Delete
    public function delete(int $id): bool
    {
        $user = $this->getById($id);
        if (!$user) return false;

        $this->em->remove($user);
        $this->em->flush();

        return true;
    }
}
