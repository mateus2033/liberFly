<?php

namespace App\Services\UserServices;

use App\Models\User;
use App\Utils\ConstantMessage\ConstantMessage;
use App\Utils\GeneralValidation\ValidateCpf;
use App\Utils\GeneralValidation\ValidateDateTime;
use App\Utils\GeneralValidation\ValidateEmail;

class UserValidationForUpdateService extends User
{

    private ValidateCpf   $validateCpf;
    private ValidateEmail $validateEmail;

    protected bool $isValid;
    public string $fileName;
    public array $message;

    public function __construct(
        ValidateCpf $validateCpf,
        ValidateEmail $validateEmail,
        ValidateDateTime $validateDateTime
    ) {
        $this->validateCpf   = $validateCpf;
        $this->validateEmail = $validateEmail;
        $this->validateDateTime  = $validateDateTime;
    }

    public function validateFormUser(array $data)
    {
        $this->validateUser($data);
        if ($this->isValid == true) {
            return $this->mountUser();
        }
        return $this;
    }

    private function mountUser(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'cpf'  => $this->getCpf(),
            'age'  => $this->getAge()
        ];
    }

    private function validateUser(array $data)
    {
        $count = 0;
        $array = [];
        $error = [];

        $array['id']    = $this->_id($data);
        $array['name']  = $this->_name($data);
        $array['cpf']   = $this->_cpf($data);
        $array['age']   = $this->_age($data);

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
     * @param array $data
     * @return string|null
     */
    private function _id(array $data)
    {
        if (!isset($data['id'])) {
            return ConstantMessage::REQUIRED;
        }

        if (!is_integer($data['id']) || $data['id'] <= 0) {
            return ConstantMessage::ONLY_INTEGER_POSITIVE;
        }

        $this->setId($data['id']);
        return null;
    }

    /**
     * @param array $data
     * @return string|null
     */
    private function _name(array $data)
    {
        if (!isset($data['name'])) {
            return ConstantMessage::REQUIRED;
        }

        if (!is_string($data['name'])) {
            return ConstantMessage::ONLY_STRING;
        }

        $this->setName($data['name']);
        return null;
    }

    /**
     * @param array $data
     * @return string|null
     */
    private function _cpf(array $data)
    {
        if (!isset($data['cpf'])) {
            return ConstantMessage::REQUIRED;
        }

        if (!is_string($data['cpf'])) {
            return ConstantMessage::ONLY_STRING;
        }

        $cpf = $this->validateCpf->validarCPF($data['cpf']);
        if (!is_null($cpf)) {
            return $cpf;
        }

        $this->setCpf($data['cpf']);
        return null;
    }

    private function _age(array $data)
    {
        if (!isset($data['age'])) {
            return ConstantMessage::REQUIRED;
        }

        if (!is_integer($data['age']) || $data['age'] < 0) {
            return ConstantMessage::ONLY_INTEGER_POSITIVE;
        }

        $this->setAge($data['age']);
        return null;
    }
}
