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
        $books = $this->booksRepository->getByReleaseDateSort();
        return $books;
    }

    // create book
    public function createBook(array $data)
    {
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
        return $book;
    }
    // get a single book by id
    public function getBookById($id)
    {
        $book = $this->booksRepository->getById($id);
        return $book;
    }
}
