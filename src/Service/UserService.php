<?php
namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserService  {

    private EntityManagerInterface $em;
    private UserRepository $userRepository;


    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
        $this->userRepository = $em->getRepository(User::class);
    }

    /**
     * Create a new User
     */
    public function createUser(string $name, string $email,
                                string $password) : User {

        $user = new User();
        $user->setName($name);
        $user->setEmail($email);
        $user->setPassword(password_hash($password, PASSWORD_BCRYPT));
        $user->setIsActive(true);

        $this->em->persist($user);
        $this->em->flush();

        return $user;

    }

    /**
     * get user by Email
     */
    public function getUserByEmail(string $email) : ?User {

        return $this->userRepository->findByEmail($email);
    }

    public function deactivateUser(User $user) : void {
        $user->setIsActive(false);
        $this->em->flush();
    }

    public function getAllActiveUsers() : array {
        return $this->userRepository->findActiveUsers();
    }


}