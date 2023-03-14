<?php

namespace App\Services\AddressService;

use App\Models\Address;
use App\Utils\ConstantMessage\ConstantMessage;
use App\Utils\GeneralValidation\ValidateCep;

class AddressValidationForUpdateService  extends Address
{
    private bool  $isValid;
    public  array $message;

    public function validFormAddress(array $data)
    {
        $this->validateAddress($data);
        if ($this->isValid == true) {
            return $this->mountAddress();
        }
        return $this;
    }

    private function mountAddress(): array
    {
        return [
            'street' => $this->getStreet(),
            'number' => $this->getNumber(),
            'city'   => $this->getCity(),
            'cep'    => $this->getCep(),
        ];
    }

    private function validateAddress(array $data)
    {
        $count = 0;
        $array = [];
        $error = [];
        $data  = collect($data);

        $array['street'] = $this->_street($data->get('street'));
        $array['number'] = $this->_number($data->get('number'));
        $array['city']   = $this->_city($data->get('city'));
        $array['cep'] = $this->_cep($data->get('cep'));

        foreach ($array as $key => $value) {
            if (!is_null($value)) {
                $error[$key] = $value;
                $count++;
            }
        }

        if ($count > 0) {
            $this->isValid = false;
            $this->message = $error;
        } else {
            $this->isValid = true;
            $this->message = $array;
        }
    }

        /**
     * @param string $street
     * @return string|null
     */
    private function _street($street)
    {
        if (!isset($street)) {
            return ConstantMessage::REQUIRED;
        }

        if (!is_string($street)) {
            return ConstantMessage::ONLY_STRING;
        }

        $this->setStreet($street);
        return null;
    }

    /**
     * @param string $number
     * @return string|null
     */
    private function _number($number)
    {
        if (!isset($number)) {
            return ConstantMessage::REQUIRED;
        }

        if (!is_integer($number) || $number < 0) {
            return ConstantMessage::ONLY_INTEGER_POSITIVE;
        }

        $this->setNumber($number);
        return null;
    }

    /**
     * @param string $city
     * @return string|null
     */
    private function _city($city)
    {
        if (!isset($city)) {
            return ConstantMessage::REQUIRED;
        }

        if (!is_string($city)) {
            return ConstantMessage::ONLY_STRING;
        }

        $this->setCity($city);
        return null;
    }

    /**
     * @param string $cep
     * @return string|null
     */
    private function _cep($cep)
    {
        if (!isset($cep)) {
            return ConstantMessage::REQUIRED;
        }

        if (!is_string($cep)) {
            return ConstantMessage::ONLY_STRING;
        }

        $cepValidation = (new ValidateCep())->validateCEP($cep);
        if (!is_bool($cepValidation)) {
            return $cepValidation;
        }

        $this->setCep($cep);
        return null;
    }
}
