<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: \App\Repository\CategoryRepository::class)]
#[ORM\Table(name: "categories")]

class Category {

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type : 'integer')]
    private int $id;

    #[ORM\Column(type : 'string' , length : 255)]
    private string $categoryName;

    #[ORM\ManyToMany(targetEntity : Article::class , mappedBy : 'categories')]
    private Collection $articles;

    public function __construct() {
        $this -> articles = new ArrayCollection();
    }

    public function getId() : int {
        return $this->id;
    }

    public function getCategoryName() : string {
        return $this->categoryName;
    }
    public function setCategoryName(string $categoryName) : void {
        $this->categoryName = $categoryName;
    }

    // --- Article Management ---
    public function getArticles(): Collection { return $this->articles; }

}