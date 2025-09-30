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
    public function safeCreateUser(string $name, string $email,
                                string $password) : User {
        $this->em->beginTransaction();

        try {
            $user = new User();
            $user->setName($name);
            $user->setEmail($email);
            $user->setPassword(password_hash($password, PASSWORD_BCRYPT));
            $user->setIsActive(true);

            $this->em->persist($user);
            $this->em->flush();
            $this->em->commit();
            return $user;
            
        } catch (\Throwable $e) {
            $this->em->rollback();
            return null;
        }

    }

    public function safeUpdateUser(User $user , array $data) : user 
    {
        $this->em->beginTransaction();
        try {
            if (isset($data["name"])) $user->setName($data["name"]);
            if (isset($data["email"])) $user->setEmail($data["email"]);
            if (isset($data["password"])) $user->setPassword(
                        password_hash($data["password"], PASSWORD_BCRYPT));
            if (isset($data["isActive"])) $user->setIsActive($data["isActive"]);

            
            $this->em->flush();
            $this->em->commit();
            return $user;

        } catch (\Throwable $e) {
            $this->em->rollback();
            return null;
        }

    }

    public function safeDeleteUser(User $user) : user 
    {
        $this->em->beginTransaction();
        try {
            $this->em->remove($user);
            $this->em->flush();
            $this->em->commit();
            return $user;

        } catch (\Throwable $e) {
            $this->em->rollback();
            return null;
        }

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