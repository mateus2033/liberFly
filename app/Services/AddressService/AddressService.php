<?php
namespace App\Services\AddressService;

use App\Models\Address;
use App\Repository\AddressRepository\AddressRepository;
use App\Utils\ErroMensage\ErroMensage;

class AddressService {

    private AddressRepository $addressRepository;
    private AddressValidationForSaveService $addressValidationForSaveService;
    private AddressValidationForUpdateService $addressValidationForUpdateService;

    public function __construct(
        AddressRepository $addressRepository,
        AddressValidationForSaveService $addressValidationForSaveService,
        AddressValidationForUpdateService $addressValidationForUpdateService
    ){
        $this->addressRepository = $addressRepository;
        $this->addressValidationForSaveService = $addressValidationForSaveService;
        $this->addressValidationForUpdateService = $addressValidationForUpdateService;
    }

    public function manageSaveUser(array $addres, int $user_id)
    {
        $address = $this->addressValidationForSaveService->validFormAddress($addres, $user_id);
        if(!is_array($address))
        {
            return ErroMensage::errorMultipleMessage($address->message);
        }

        $this->addressRepository->create($address);
        return true;
    }

    public function manageUpdateAdderess(array $address, Address $currentAddress)
    {
        $address = $this->addressValidationForUpdateService->validFormAddress($address);
        if($address instanceof AddressValidationForUpdateService)
        {
            return ErroMensage::errorMultipleMessage($address->message);
        }

        return $this->addressRepository->update($currentAddress, $address);
    }
}
