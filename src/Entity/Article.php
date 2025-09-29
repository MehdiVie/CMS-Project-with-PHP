<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: \App\Repository\ArticleRepository::class)]
#[ORM\Table(name: "articles")]

class Article {

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type : 'integer')]
    private int $id;

    #[ORM\Column(type : 'string' , length : 255)]
    private string $title;

    #[ORM\Column(type : 'text')]
    private string $content;

    #[ORM\ManyToOne(targetEntity : User::class)]
    #[ORM\JoinColumn(nullable : false)]
    private User $author;

    #[ORM\ManyToMany(targetEntity : Category::class)]
    #[ORM\JoinTable(
        name : 'article_categories' , 
        joinColumns : [new ORM\JoinColumn(name : "article_id" , referencedColumnName : 'id')] ,
        inverseJoinColumns : [new ORM\JoinColumn(name : 'category_id' , referencedColumnName : 'id')]
    )]
    private Collection $categories;
    
    #[ORM\Column(type : "datetime")]
    private \DateTime $created_at;

    public function __construct(){
        $this->created_at = new \DateTime();
        $this->categories = new ArrayCollection();
    }

    public function getId() : int {
        return $this->id;
    }

    public function getTitle() : string {
        return $this->title;
    }
    public function setTitle(string $title) : void {
        $this->title = $title;
    }

    public function getContent() : string {
        return $this->content;
    }
    public function setContent(string $content) : void {
        $this->content = $content;
    }

    public function getAuthor() : User {
        return $this->author;
    }
    public function setAuthor(User $author) : void {
        $this->author = $author;
    }

        public function getCreatedAt() : \DateTime {
        return $this->created_at;
    }

    // --- Category Management ---

    public function getCategories(): Collection { return $this->categories; }

    public function addCategory(Category $category): void
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }
    }

    public function removeCategory(Category $category): void
    {
        $this->categories->removeElement($category);
    }


}