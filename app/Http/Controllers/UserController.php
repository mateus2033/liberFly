<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Utils\ErroMensage\ErroMensage;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\User\UserListResource;
use App\Utils\SuccessMessage\SuccessMessage;
use App\Services\UserServices\UserService;
use Exception;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    private User $user;
    private Address $address;
    private UserService $userService;

    public function __construct(
        User $user,
        Address $address,
        UserService $userService
    ) {
        $this->user = $user;
        $this->address = $address;
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $response = $this->userService->listUser();
        if ($response->isEmpty())
            return response()->json($response, Response::HTTP_BAD_REQUEST);
        else
            return response()->json(new UserListResource($response), Response::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     path="/api/user/show",
     *     summary="Get user information",
     *     tags={"Users"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="user_id",
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
     *             @OA\Property(property="id",      type="integer", example="59"),
     *             @OA\Property(property="name",    type="string", example="John"),
     *             @OA\Property(property="email",   type="string", example="email@email.com"),
     *             @OA\Property(property="age",     type="string", example=29),
     *             @OA\Property(property="cpf",     type="string", example="739.073.280-60"),
     *             @OA\Property(property="address", type="array", @OA\Items(type="array", @OA\Items())),
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
        $response = $this->userService->showUser((int) $request->user_id);
        if (is_array($response))
            return response()->json($response, Response::HTTP_NOT_FOUND);
        else
            return response()->json(new UserResource($response), Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     tags={"Session"},
     *     path="/api/account/store",
     *     summary="Armazenar um novo usuário",
     *     description="Armazena um novo usuário no sistema.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name",  type="string", example="John"),
     *             @OA\Property(property="email", type="string", example="john@gmail.com"),
     *             @OA\Property(property="age",   type="integer", example=25),
     *             @OA\Property(property="cpf",   type="string", example="638.952.380-74"),
     *             @OA\Property(property="password", type="string", example="12345678"),
     *             @OA\Property(property="street",   type="string", example="Rua A"),
     *             @OA\Property(property="number",   type="integer", example=100),
     *             @OA\Property(property="city",     type="string", example="Valaquia"),
     *             @OA\Property(property="cep",      type="string", example="29181-111"),
     * )
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="Usuário criado com sucesso",
     *         @OA\JsonContent(type="array",@OA\Items(ref="#/components/schemas/User"))),
     *
     *     @OA\Response(
     *         response="400",
     *         description="Erro de Validação",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="{name:required, cep:required}")
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
            DB::beginTransaction();
            $user = $request->only($this->user->getModel()->getFillable());
            $address = $request->only($this->address->getModel()->getFillable());
            $response = $this->userService->manageSaveUser($user, $address);
            if ($response instanceof User) {
                DB::commit();
                return response()->json(new UserResource($response), Response::HTTP_CREATED);
            }
            return response()->json($response, Response::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            DB::rollBack();
            return ErroMensage::errorMessage($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @OA\Put(
     *     path="/api/user/update",
     *     tags={"Users"},
     *     security={{"bearerAuth":{}}},
     *     summary="Atualizar um usuário",
     *     description="Atualizar um usuário do sistema.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id",   type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="John"),
     *             @OA\Property(property="age",  type="string", example="Tereuba"),
     *             @OA\Property(property="cpf",  type="string", example="999.888.777.66"),
     *             @OA\Property(property="street", type="string", example="Rua 50"),
     *             @OA\Property(property="number", type="string", example=777),
     *             @OA\Property(property="city",   type="string", example="Valaquia"),
     *             @OA\Property(property="cep",    type="string", example="57306-46"),
     * )
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="Atualizado com sucesso",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/User")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado",
     *         @OA\JsonContent(@OA\Property(property="error", type="string", example="Token sem autorização."))
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
            DB::beginTransaction();
            $user = $request->only($this->user->getModel()->getFillable());
            $address = $request->only($this->address->getModel()->getFillable());
            $response = $this->userService->manageUpdateUser($user, $address);
            if ($response instanceof User) {
                DB::commit();
                return response()->json(new UserResource($response), Response::HTTP_CREATED);
            }
            return response()->json($response, Response::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            DB::rollBack();
            return ErroMensage::errorMessage($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/user/delete",
     *     tags={"Users"},
     *     security={{"bearerAuth":{}}},
     *     summary="Deletar um usuário",
     *     description="Deletar um novo usuário no sistema.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="user_id", type="integer", example=1),
     *          )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Operation successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/User")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado",
     *         @OA\JsonContent(@OA\Property(property="error", type="string", example="Token sem autorização."))
     *     ),
     *      @OA\Response(
     *         response=400,
     *         description="Erro ao atualizar o usuário",
     *         @OA\JsonContent(@OA\Property(property="error", type="string", example="Usuario não encontrado."))
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
            DB::beginTransaction();
            $response = $this->userService->manageDeleteUser((int) $request->id);
            if (is_array($response))
                return response()->json($response, Response::HTTP_BAD_REQUEST);
            else
                DB::commit();
            return response()->json(SuccessMessage::sucessMessage($response), Response::HTTP_OK);
        } catch (Exception $e) {
            DB::rollBack();
            return ErroMensage::errorMessage($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @OA\Post(
     *     tags={"Users"},
     *     path="/api/user/addbook",
     *     summary="Adicionar um livro",
     *     security={{"bearerAuth":{}}},
     *     description="Adicionar um livro a um usuário.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="book_id", type="integer", example=10)),
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="Usuário criado com sucesso",
     *         @OA\JsonContent(type="array",@OA\Items(ref="#/components/schemas/User"))),
     *
     *     @OA\Response(
     *         response="400",
     *         description="Erro de Validação",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="{error:userNotFound, error:Book no found }"),
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
    public function addbook(Request $request)
    {
        try {
            $response = $this->userService->manageAddBook((int) $request->user_id, (int) $request->book_id);
            if (is_array($response))
                return response()->json($response, Response::HTTP_BAD_REQUEST);
            else
                return response()->json(new UserResource($response), Response::HTTP_OK);
        } catch (Exception $e) {
            return ErroMensage::errorMessage($e->getMessage(), $e->getCode());
        }
    }
}
