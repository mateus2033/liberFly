<?php
namespace App\Services\UserServices;

use App\Repository\UserRepository\UserRepository;
use App\Services\AddressService\AddressService;
use App\Services\BookServices\BookService;
use App\Utils\ConstantMessage\ConstantMessage;
use App\Utils\ErroMensage\ErroMensage;
use App\Utils\SuccessMessage\SuccessMessage;

class UserService {

    private BookService $bookService;
    private UserRepository $userRepository;
    private AddressService $addressService;
    private UserValidationForSaveService $userValidationForSaveService;
    private UserValidationForUpdateService $userValidationForUpdateService;

    public function __construct(
        BookService $bookService,
        UserRepository $userRepository,
        AddressService $addressService,
        UserValidationForSaveService $userValidationForSaveService,
        UserValidationForUpdateService $userValidationForUpdateService
    ){
        $this->bookService = $bookService;
        $this->userRepository = $userRepository;
        $this->addressService = $addressService;
        $this->userValidationForSaveService = $userValidationForSaveService;
        $this->userValidationForUpdateService = $userValidationForUpdateService;
    }

    public function listUser()
    {
        $users = $this->userRepository->getAll();
        if ($users->isNotEmpty()) return $users;
        else
            return ErroMensage::errorMessage(ConstantMessage::USERNOTFOUND);
    }

    public function showUser(int $user_id)
    {
        $user = $this->userRepository->findById($user_id);
        if (!is_null($user)) {
            return $user;
        }
        return ErroMensage::errorMessage(ConstantMessage::USERNOTFOUND);
    }

    public function manageSaveUser(array $user, array $address)
    {
        $user = $this->userValidationForSaveService->validateFormUser($user);
        if($user instanceof UserValidationForSaveService)
        {
            return ErroMensage::errorMultipleMessage($user->message);
        }

        $user = $this->userRepository->create($user);
        $address = $this->addressService->manageSaveUser($address, $user->id);
        if(!is_bool($address))
        {
            return $address;
        }
        return $user;
    }

    public function manageUpdateUser(array $user, array $address)
    {
        $userValid = $this->userValidationForUpdateService->validateFormUser($user);
        if($userValid instanceof UserValidationForUpdateService)
        {
            return ErroMensage::errorMultipleMessage($userValid->message);
        }

        $user = $this->showUser($userValid['id']);
        if(is_array($user))
        {
            return $user;
        }

        $this->userRepository->update($user, $userValid);
        $address = $this->addressService->manageUpdateAdderess($address, $user->address);
        if(is_array($address))
        {
            return ErroMensage::errorMultipleMessage($address);
        }
        return $user;
    }

    public function manageDeleteUser(int $user_id)
    {
        $user = $this->showUser($user_id);
        if(is_array($user))
        {
            return $user;
        }

        $user->address()->delete();
        if($user)
        {
            $this->userRepository->destroy($user->id);
            return ConstantMessage::USER_DELETED;
        }

        return ErroMensage::errorMessage(ConstantMessage::ERRO_TO_DELETE_USER);
    }

    public function manageAddBook(int $user_id, int $book_id)
    {
        $user = $this->showUser($user_id);
        if(is_array($user))
        {
            return $user;
        }

        $book = $this->bookService->showBook($book_id);
        if (is_array($book)) {
            return $book;
        }

        if($book->user_id != null &&  $book->user_id != $user_id )
        {
            return ErroMensage::errorMessage(ConstantMessage::BOOK_UNAVAILABLE);
        }

        $this->bookService->addUserToBook($book, $user->id);
        return $user;
    }
}
