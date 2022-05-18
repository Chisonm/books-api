<?php
namespace App\Repositories;

use App\Models\Book;

class BooksRepository
{
    protected $book;

    public function __construct(Book $book)
    {
        $this->book = $book;
    }

    public function getAll()
    {
        return $this->book->all();
    }

    public function getById($id)
    {
        return $this->book->find($id);
    }

    public function create(array $data)
    {
        return $this->book->create($data);
    }

    // sort by release date from earliest to latest
    public function getByReleaseDateSort()
    {
        return $this->book->latest('released')->get();
    }
}
