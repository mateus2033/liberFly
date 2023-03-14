<?php

namespace App\Repository\BookRepository;

use App\Interfaces\BookInterface\BookRepositoryInterface;
use App\Models\Book;

class BookRepository implements BookRepositoryInterface
{

    protected Book $model;

    public function __construct(Book $model)
    {
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function getBookByName(string $bookName)
    {
        return $this->model->all()->where('name', $bookName);
    }

    public function findById(int $id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(Book $book, array $data)
    {
        return $book->update($data);
    }

    public function destroy(int $id)
    {
        return $this->model->destroy($id);
    }
}
