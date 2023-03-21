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

    /**
     * @OA\Get(
     *     path="/api/books/index",
     *     summary="Get All Books",
     *     tags={"Book"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response="200",
     *         description="Returns user information",
     *          @OA\JsonContent(
     *             @OA\Property(property="id",   type="integer", example="59"),
     *             @OA\Property(property="name", type="string", example="John"),
     *             @OA\Property(property="author",     type="string", example="email@email.com"),
     *             @OA\Property(property="edition",    type="string", example=29),
     *             @OA\Property(property="publishing", type="string", example="739.073.280-60"),
     *             @OA\Property(property="user", type="array", @OA\Items(type="array", @OA\Items())),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Unauthorized"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="User not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="User not found"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="User not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Ocorreu um erro interno no servidor"),
     *         ),
     *     ),
     * )
     */
    public function index(Request $request)
    {
        $books = $this->bookService->listBooks();
        if (is_array($books))
            return response()->json($books, Response::HTTP_NOT_FOUND);
        else
            return response()->json(new BookListResource($books), Response::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     path="/api/books/show",
     *     summary="Get Books information",
     *     tags={"Book"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         required=true,
     *         description="ID of the user to get",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *             example=1
     *         ),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Returns user information",
     *          @OA\JsonContent(
     *             @OA\Property(property="id",   type="integer", example="59"),
     *             @OA\Property(property="name", type="string", example="John"),
     *             @OA\Property(property="author",     type="string", example="email@email.com"),
     *             @OA\Property(property="edition",    type="string", example=29),
     *             @OA\Property(property="publishing", type="string", example="739.073.280-60"),
     *             @OA\Property(property="user", type="array", @OA\Items(type="array", @OA\Items())),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Unauthorized"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="User not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="User not found"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="User not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Ocorreu um erro interno no servidor"),
     *         ),
     *     ),
     * )
     */
    public function show(Request $request)
    {
        $book = $this->bookService->showBook((int) $request->id);
        if (is_array($book))
            return response()->json($book, Response::HTTP_NOT_FOUND);
        else
            return response()->json(new BookResource($book), Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     tags={"Book"},
     *     path="/api/books/store",
     *     summary="Armazenar um novo livro",
     *     description="Armazena um novo livro no sistema.",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id",    type="string", example=1),
     *             @OA\Property(property="name",  type="string", example="Quimica"),
     *             @OA\Property(property="author", type="string", example="Jony"),
     *             @OA\Property(property="edition",   type="integer", example="5"),
     *             @OA\Property(property="publishing",   type="string", example="Minas Book"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="livro criado com sucesso",
     *             @OA\JsonContent(@OA\Property(property="id", type="string", example=1),
     *             @OA\Property(property="name",       type="string", example="As Aventuras de PII"),
     *             @OA\Property(property="author",     type="string", example="Nicolau Quinto"),
     *             @OA\Property(property="edition",    type="string", example="Segunda"),
     *             @OA\Property(property="publishing", type="string", example="Ed Sentopeia")
     *          ),
     *      ),
     *     @OA\Response(
     *         response="400",
     *         description="Erro de Validação",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="{name:required, author:required, edition:required, publishing:required}")
     *         )
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Erro interno do servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Ocorreu um erro interno no servidor.")
     *         )
     *     ),
     * )
     */
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

    /**
     * @OA\Put(
     *     path="/api/books/update",
     *     tags={"Book"},
     *     security={{"bearerAuth":{}}},
     *     summary="Atualizar um Livro",
     *     description="Atualizar um livro.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id",        type="integer", example=1),
     *             @OA\Property(property="name",      type="string", example="As Aventuras de PI25"),
     *             @OA\Property(property="author",    type="string", example="Nicolau Quinto"),
     *             @OA\Property(property="edition",   type="string", example="Terceira"),
     *             @OA\Property(property="publishing",type="string", example="Ed Alabama"),
     *          ),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="livro atualizado com sucesso",
     *             @OA\Property(property="id",        type="integer", example=1),
     *             @OA\JsonContent(@OA\Property(property="id", type="string", example=1),
     *             @OA\Property(property="name",       type="string", example="As Aventuras de PII"),
     *             @OA\Property(property="author",     type="string", example="Nicolau Quinto"),
     *             @OA\Property(property="edition",    type="string", example="Segunda"),
     *             @OA\Property(property="publishing", type="string", example="Ed Sentopeia"),),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado",
     *         @OA\JsonContent(@OA\Property(property="error", type="string", example="Authorization Token not found."))
     *     ),
     *      @OA\Response(
     *         response=400,
     *         description="Erro ao atualizar o usuário",
     *         @OA\JsonContent(@OA\Property(property="error", type="string", example="Erro de validação."))
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao atualizar o usuário",
     *         @OA\JsonContent(@OA\Property(property="error", type="string", example="Erro interno no servidor."))
     *     ),
     *  ),
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/api/books/delete",
     *     tags={"Book"},
     *     security={{"bearerAuth":{}}},
     *     summary="Deletar um livro",
     *     description="Deletar um livro no sistema.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *          )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Operation successfully",
     *         @OA\JsonContent(@OA\Property(property="message", type="string", example="Book deleted."))
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado",
     *         @OA\JsonContent(@OA\Property(property="error", type="string", example="Authorization Token not found."))
     *     ),
     *      @OA\Response(
     *         response=400,
     *         description="Erro ao atualizar o usuário",
     *         @OA\JsonContent(@OA\Property(property="error", type="string", example="Book not found."))
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao atualizar o usuário",
     *         @OA\JsonContent(@OA\Property(property="error", type="string", example="Erro interno no servidor."))
     *     ),
     *  ),
     * )
     */
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
