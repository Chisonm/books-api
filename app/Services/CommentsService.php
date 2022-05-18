<?php
namespace App\Services;

use InvalidArgumentException;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\CommentsResource;
use App\Repositories\CommentsRepository;
use App\utils\ApiCustomResponse;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

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
        try {
            $comments = $this->commentsRepository->getAll();

            if (!$comments) {
                $message = "No comments found!";
                return ApiCustomResponse::errorResponse($message, Response::HTTP_NO_CONTENT);
            }
            $message = "Comments retrieved successfully!";
            return ApiCustomResponse::successResponse($message, CommentsResource::collection($comments), Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // create comment
    public function createComment(array $data)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($data, [
            'book_id' => 'required|integer|exists:books,id',
            'name' => 'required|string|max:255',
            'body' => 'required|string|max:255',
        ]);
            if ($validator->fails()) {
                throw new InvalidArgumentException($validator->errors()->first());
            }

            $data['ip'] = request()->ip();
            $comment = $this->commentsRepository->create($data);
            $message = "Comment created successfully!";
            DB::commit();
            return ApiCustomResponse::successResponse($message, new CommentsResource($comment), Response::HTTP_CREATED);
        } catch (InvalidArgumentException $e) {
            DB::rollback();
            $message = $e->getMessage();
            return ApiCustomResponse::errorResponse($message, Response::HTTP_UNPROCESSABLE_ENTITY, $e);
        } catch (\Exception $e) {
            DB::rollback();
            $message = 'Something went wrong while processing your request.';
            return ApiCustomResponse::errorResponse($message, Response::HTTP_INTERNAL_SERVER_ERROR, $e);
        }
    }
}
