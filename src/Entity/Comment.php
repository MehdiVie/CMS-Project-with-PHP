<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "comments")]

class Comment {

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type : 'integer')]
    private int $id;

    #[ORM\Column(type : 'text')]
    private string $content;

    #[ORM\ManyToOne(targetEntity : User::class)]
    #[ORM\JoinColumn(nullable : false)]
    private User $author;

    #[ORM\ManyToOne(targetEntity : Article::class)]
    #[ORM\JoinColumn(nullable : false)]
    private Article $article;

    #[ORM\Column(type : "datetime")]
    private \DateTime $created_at;

    public function __construct(){
        $this->created_at = new \DateTime();
    }

    public function getId() : int {
        return $this->id;
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

    public function getArticle() : Article {
        return $this->article;
    }
    public function setArticle(Article $article) : void {
        $this->article = $article;
    }

    public function getCreatedAt() : \DateTime {
        return $this->created_at;
    }

}