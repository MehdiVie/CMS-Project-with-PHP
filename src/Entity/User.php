<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: \App\Repository\UserRepository::class)]
#[ORM\Table(name: "users")]

class User {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "string", length: 255)]
    private string $name;

    #[ORM\Column(type: "string", length: 255 , unique : true)]
    private string $email;

    #[ORM\Column(type: "string", length: 255)]
    private string $password;

    #[ORM\Column(type: "boolean")]
    private bool $isActive = true;

    public function getId() : int {
        return $this->id;
    }

    public function getName() : string {
        return $this->name;
    }
    public function setName(string $name) : void {
        $this->name = $name;
    }

    public function getEmail() : string {
        return $this->email;
    }
    public function setEmail(string $email) : void {
        $this->email = $email;
    }

    public function getPassword() : string {
        return $this->password;
    }
    public function setPassword(string $password) : void {
        $this->password = $password;
    }

    public function getIsActive() : bool {
        return $this->isActive;
    }
    public function setIsActive(bool $isActive) : void {
        $this->isActive = $isActive;
    }
}