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

    public function show(Request $request)
    {
        $response = $this->userService->showUser((int) $request->user_id);
        if (is_array($response))
            return response()->json($response, Response::HTTP_NOT_FOUND);
        else
            return response()->json(new UserResource($response), Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $user = $request->only($this->user->getModel()->getFillable());
            $address = $request->only($this->address->getModel()->getFillable());
            $response = $this->userService->manageSaveUser($user, $address);
            if ($response instanceof User){
                DB::commit();
                return response()->json(new UserResource($response), Response::HTTP_CREATED);
            }
                return response()->json($response, Response::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            DB::rollBack();
            return ErroMensage::errorMessage($e->getMessage(), $e->getCode());
        }
    }

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

    public function addbook(Request $request)
    {
        try{
            $response = $this->userService->manageAddBook((int) $request->user_id, (int) $request->book_id);
            if (is_array($response))
                return response()->json($response, Response::HTTP_BAD_REQUEST);
            else
            return response()->json(new UserResource($response), Response::HTTP_OK);

        }catch(Exception $e){
            return ErroMensage::errorMessage($e->getMessage(), $e->getCode());
        }
    }
}
