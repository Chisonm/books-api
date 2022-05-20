<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\utils\ApiCustomResponse;
use App\Services\CommentsService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\CommentsResource;
use Symfony\Component\HttpFoundation\Response;

class CommentsController extends Controller
{
    protected $commentService;

    public function __construct(CommentsService $commentService)
    {
        $this->commentService = $commentService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $comments = $this->commentService->getAll();
            $message = "Comments retrieved successfully!";
            return ApiCustomResponse::successResponse($message, CommentsResource::collection($comments), Response::HTTP_OK);
        } catch (\Exception $e) {
            $message = 'Something went wrong while processing your request.';
            return ApiCustomResponse::errorResponse($message, Response::HTTP_INTERNAL_SERVER_ERROR, $e);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $comment = $this->commentService->createComment($request->all());
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
