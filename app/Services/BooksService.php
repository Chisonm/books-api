<?php
namespace App\Services;

use InvalidArgumentException;
use App\utils\ApiCustomResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\BooksResource;
use App\Repositories\BooksRepository;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class BooksService
{
    protected $booksRepository;

    public function __construct(BooksRepository $booksRepository)
    {
        $this->booksRepository = $booksRepository;
    }

    // get all books
    public function getAll()
    {
        try {
            $books = $this->booksRepository->getByReleaseDateSort();
            $message = "Books retrieved successfully!";
            return ApiCustomResponse::successResponse($message, BooksResource::collection($books), Response::HTTP_OK);
        } catch (\Exception $e) {
            $message = 'Something went wrong while processing your request.';
            return ApiCustomResponse::errorResponse($message, Response::HTTP_INTERNAL_SERVER_ERROR, $e);
        }
    }

    // create book
    public function createBook(array $data)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($data, [
            'title' => 'required|string|max:255',
            'released' => 'required|date',
            'authors' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'publisher' => 'required|string',
            'number_of_pages' => 'required|numeric',
            'isbn' => 'required|numeric',
        ]);
            if ($validator->fails()) {
                throw new InvalidArgumentException($validator->errors()->first());
            }

            $data['released'] = date('Y-m-d', strtotime($data['released']));
            // convert authors to array
            $authors = explode(',', $data['authors']);
            $data['authors'] = $authors;

            $book = $this->booksRepository->create($data);
            $message = "Book created successfully!";
            DB::commit();
            return ApiCustomResponse::successResponse($message, new BooksResource($book), Response::HTTP_CREATED);
        }
        catch(InvalidArgumentException $e){
            DB::rollback();
            $message = $e->getMessage();
            return ApiCustomResponse::errorResponse($message, Response::HTTP_UNPROCESSABLE_ENTITY, $e);
        }
        catch(\Exception $e){
            DB::rollback();
            $message = 'Something went wrong while processing your request.';
            return ApiCustomResponse::errorResponse($message, Response::HTTP_INTERNAL_SERVER_ERROR, $e);
        }
    }
    // get a single book by id
    public function getBookById($id)
    {
        try {
            $book = $this->booksRepository->getById($id);
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
