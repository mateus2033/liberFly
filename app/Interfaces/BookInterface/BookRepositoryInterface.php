<?php

namespace App\Interfaces\BookInterface;

use App\Models\Book;

interface BookRepositoryInterface
{
    public function getAll();
    public function findById(int $id);
    public function create(array $data);
    public function update(Book $address, array $data);
    public function destroy(int $id);
}
