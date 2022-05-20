<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use InvalidArgumentException;
use App\Services\BooksService;
use App\utils\ApiCustomResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\BooksResource;
use Symfony\Component\HttpFoundation\Response;

class BooksController extends Controller
{
    protected $booksService;

    public function __construct(BooksService $booksService)
    {
        $this->booksService = $booksService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $books = $this->booksService->getAll();
            $message = "Books retrieved successfully!";
            return ApiCustomResponse::successResponse($message, BooksResource::collection($books), Response::HTTP_OK);
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
            $book = $this->booksService->createBook($request->all());
            $message = "Book created successfully!";
            DB::commit();
            return ApiCustomResponse::successResponse($message, new BooksResource($book), Response::HTTP_CREATED);
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $book = $this->booksService->getBookById($id);
            if (!$book) {
                $message = "No books found";
                return ApiCustomResponse::errorResponse($message, Response::HTTP_NOT_FOUND);
            }
            $message = "Book retrieved successfully!";
            return ApiCustomResponse::successResponse($message, new BooksResource($book), Response::HTTP_OK);
        } catch (\Exception $e) {
            $message = 'Something went wrong while processing your request.';
            return ApiCustomResponse::errorResponse($message, Response::HTTP_INTERNAL_SERVER_ERROR, $e);
        }
    }
}
