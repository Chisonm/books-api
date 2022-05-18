<?php
namespace App\Repositories;
use App\Models\Comment;

class CommentsRepository
{
    protected $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function getAll()
    {
        return $this->comment->all();
    }

    public function getById($id)
    {
        return $this->comment->find($id);
    }

    public function create(array $attributes)
    {
        return $this->comment->create($attributes);
    }
}
