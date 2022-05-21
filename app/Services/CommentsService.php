<?php
namespace App\Services;

use InvalidArgumentException;
use App\utils\ApiCustomResponse;
use App\Repositories\CommentsRepository;
use Illuminate\Support\Facades\Validator;

class CommentsService
{
    protected $commentsRepository;

    public function __construct(CommentsRepository $commentsRepository)
    {
        $this->commentsRepository = $commentsRepository;
    }

    // get all comments
    public function getAll()
    {
        $comments = $this->commentsRepository->getAll();
        return $comments;
    }

    // create comment
    public function createComment(array $data)
    {
        $validator = Validator::make($data, [
            'book_id' => 'required|integer|exists:books,id',
            'name' => 'required|string|max:255',
            'body' => 'required|string|max:500',
        ]);
        if($validator->fails()) {
             throw new InvalidArgumentException($validator->errors()->first());
        }

        $data['ip'] = request()->ip();
        $comment = $this->commentsRepository->create($data);
        return $comment;
    }
}
