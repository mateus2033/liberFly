<?php

namespace App\Http\Controllers;

use App\Http\Resources\Book\BookListResource;
use App\Http\Resources\Book\BookResource;
use App\Models\Book;
use App\Services\BookServices\BookService;
use App\Utils\ErroMensage\ErroMensage;
use App\Utils\SuccessMessage\SuccessMessage;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BookController extends Controller
{
    private Book $book;
    private BookService $bookService;

    public function __construct(
        Book $book,
        BookService $bookService
    ) {
        $this->book = $book;
        $this->bookService = $bookService;
    }

    public function index(Request $request)
    {
        $books = $this->bookService->listBooks();
        if (is_array($books))
            return response()->json($books, Response::HTTP_NOT_FOUND);
        else
            return response()->json(new BookListResource($books), Response::HTTP_OK);
    }

    public function show(Request $request)
    {
        $book = $this->bookService->showBook((int) $request->id);
        if (is_array($book))
            return response()->json($book, Response::HTTP_NOT_FOUND);
        else
            return response()->json(new BookResource($book), Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        try {
            $book = $request->only($this->book->getModel()->getFillable());
            $book = $this->bookService->manageSaveBook($book);
            if (!is_array($book))
                return response()->json(new BookResource($book), Response::HTTP_CREATED);
            else
                return response()->json($book, Response::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            return ErroMensage::errorMessage($e->getMessage(), $e->getCode());
        }
    }

    public function update(Request $request)
    {
        try {
            $book = $request->only($this->book->getModel()->getFillable());
            $book = $this->bookService->manageUpdateBook($book);
            if (!is_array($book))
                return response()->json(new BookResource($book), Response::HTTP_CREATED);
            else
                return response()->json($book, Response::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            return ErroMensage::errorMessage($e->getMessage(), $e->getCode());
        }
    }

    public function delete(Request $request)
    {
        try {
            $response = $this->bookService->manageDeleteBook((int) $request->id);
            if (is_array($response))
                return response()->json($response, Response::HTTP_BAD_REQUEST);
            else
                return response()->json(SuccessMessage::sucessMessage($response), Response::HTTP_OK);
        } catch (Exception $e) {
            return ErroMensage::errorMessage($e->getMessage(), $e->getCode());
        }
    }
}
