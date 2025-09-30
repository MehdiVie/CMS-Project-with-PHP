<?php
namespace App\Controller;

use App\Service\CommentService;
use App\Service\ArticleService;
use App\Service\UserService;
use App\Entity\Comment;
use App\Entity\Article;
use App\Entity\User;

class CommentController
{
    private CommentService $commentService;
    private ArticleService $articleService;
    private UserService $userService;

    public function __construct(
        CommentService $commentService,
        ArticleService $articleService,
        UserService $userService
    ) {
        $this->commentService = $commentService;
        $this->articleService = $articleService;
        $this->userService = $userService;
    }

    public function createComment(string $content, User $author, Article $article): Comment
    {
        $comment = $this->commentService->safeCreateComment($content, $author, $article);
        if (!$comment) throw new \RuntimeException("Failed to create comment");
        echo "Comment created: {$comment->getContent()} (from {$author->getEmail()})\n";
        return $comment;
    }

    public function listCommentsByArticle(Article $article): void
    {
        $comments = $this->commentService->getCommentsByArticle($article);
        echo "Comments for Article '{$article->getTitle()}':\n";
        foreach ($comments as $c) {
            echo "- {$c->getContent()} (by {$c->getAuthor()->getEmail()})\n";
        }
    }

    
    public function updateComment(Comment $comment, 
                                string $newContent): ?Comment
    {
        return $this->commentService->safeUpdateComment($comment, $newContent);
    }


    public function deleteComment(Comment $comment): bool
    {
        $success = $this->commentService->safeDeleteComment($comment);
        echo $success ? "Comment deleted successfully.\n" : "Failed to delete comment.\n";
        return $success;
    }
}
