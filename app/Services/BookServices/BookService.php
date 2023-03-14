<?php

namespace App\Services\BookServices;

use App\Interfaces\BookInterface\BookServiceInterface;
use App\Models\Book;
use App\Repository\BookRepository\BookRepository;
use App\Utils\ConstantMessage\ConstantMessage;
use App\Utils\ErroMensage\ErroMensage;

class BookService implements BookServiceInterface
{

    private Book $book;
    private BookRepository $bookRepository;
    private BookValidationForSaveService $bookValidationForSaveService;
    private BookValidationForUpdateService $bookValidationForUpdateService;

    public function __construct(
        Book $book,
        BookRepository $bookRepository,
        BookValidationForSaveService $bookValidationForSaveService,
        BookValidationForUpdateService $bookValidationForUpdateService
    ) {
        $this->book = $book;
        $this->bookRepository = $bookRepository;
        $this->bookValidationForSaveService = $bookValidationForSaveService;
        $this->bookValidationForUpdateService = $bookValidationForUpdateService;
    }

    public function listBooks()
    {
        $books = $this->bookRepository->getAll();
        if ($books->isNotEmpty()) return $books;
        else
            return ErroMensage::errorMessage(ConstantMessage::BOOK_NOT_FOUND);
    }

    public function showBook(int $book_id)
    {
        $book = $this->bookRepository->findById($book_id);
        if (!is_null($book)) {
            return $book;
        }
        return ErroMensage::errorMessage(ConstantMessage::BOOK_NOT_FOUND);
    }

    public function getBooByName(string $bookName)
    {
        $book = $this->bookRepository->getBookByName($bookName);
        if (!$book->isEmpty()) {
            return ErroMensage::errorMessage(ConstantMessage::BOOK_EXISTS);
        }
        return true;
    }

    public function getBookByNameForUpdate(int $id, string $bookName)
    {
        $book = $this->bookRepository->getBookByName($bookName)->first();
        if ($book != null && $book->id != $id) {
            return ErroMensage::errorMessage(ConstantMessage::BOOK_EXISTS);
        }
        return true;
    }

    public function manageSaveBook(array $book)
    {
        $book = $this->bookValidationForSaveService->validateFormBook($book);
        if ($book instanceof BookValidationForSaveService) {
            return ErroMensage::errorMultipleMessage($book->message);
        }

        $bookExist = $this->getBooByName($book['name']);
        if (is_bool($bookExist)) {
            $book = $this->bookRepository->create($book);
            return $book;
        }
        return $bookExist;
    }

    public function manageUpdateBook(array $book)
    {
        $book = $this->bookValidationForUpdateService->validateFormBook($book);
        if ($book instanceof BookValidationForUpdateService) {
            return ErroMensage::errorMultipleMessage($book->message);
        }

        $bookIsValid = $this->bookIsValid($book);
        if (is_array($bookIsValid)) {
            return $bookIsValid;
        }

        $this->bookRepository->update($bookIsValid, $book);
        return $this->showBook($book['id']);
    }

    public function bookIsValid(array $book)
    {
        $getBook = $this->showBook($book['id']);
        if (is_array($getBook)) {
            return $getBook;
        }

        $isValid = $this->getBookByNameForUpdate($book['id'], $book['name']);
        if (!is_bool($isValid)) {
            return ErroMensage::errorMessage(ConstantMessage::BOOK_EXISTS);
        }

        return $getBook;
    }

    public function manageDeleteBook(int $id)
    {
        $book = $this->showBook($id);
        if(is_array($book))
        {
            return $book;
        }

        if(!$book->user)
        {
            $this->bookRepository->destroy($book->id);
            return ConstantMessage::BOOK_DELETED;
        }

        return ErroMensage::errorMessage(ConstantMessage::BOOK_CONNECTED);
    }

    public function addUserToBook(Book $book, int $user_id)
    {
        return $this->bookRepository->update($book, ['user_id' => $user_id]);
    }
}
