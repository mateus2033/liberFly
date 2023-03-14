<?php

namespace App\Services\UserServices;

use App\Models\User;
use App\Utils\ConstantMessage\ConstantMessage;
use App\Utils\GeneralValidation\ValidateCpf;
use App\Utils\GeneralValidation\ValidateDateTime;
use App\Utils\GeneralValidation\ValidateEmail;
use Illuminate\Support\Facades\Hash;

class UserValidationForSaveService extends User
{

    private ValidateCpf   $validateCpf;
    private ValidateEmail $validateEmail;

    protected bool $isValid;
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
            'name' => $this->getName(),
            'cpf'  => $this->getCpf(),
            'age'  => $this->getAge(),
            'email' => $this->getEmail(),
            'password' => $this->getPassword(),
            'email_verified_at' => $this->getEmail_verified_at(),
            'permission_id' => $this->getPermissionId()
        ];
    }

    private function validateUser(array $data)
    {
        $count = 0;
        $array = [];
        $error = [];

        $array['name']  = $this->_name($data);
        $array['cpf']   = $this->_cpf($data);
        $array['age']   = $this->_age($data);
        $array['email']     = $this->_email($data);
        $array['password']  = $this->_password($data);
        $array['email_verified_at'] = $this->_email_verified_at($data);
        $array['permission'] = $this->_permission();

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
    private function _name(array $data)
    {
        if (!isset($data['name']))
        {
            return ConstantMessage::REQUIRED;
        }

        if (!is_string($data['name']))
        {
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
        if (!isset($data['cpf']))
        {
            return ConstantMessage::REQUIRED;
        }

        if (!is_string($data['cpf']))
        {
            return ConstantMessage::ONLY_STRING;
        }

        $cpf = $this->validateCpf->validarCPF($data['cpf']);

        if (!is_null($cpf))
        {
            return $cpf;
        }

        $this->setCpf($data['cpf']);
        return null;
    }


    /**
     * @param array $data
     * @return string|null
     */
    private function _email(array $data)
    {
        if (!isset($data['email'])) {
            return ConstantMessage::REQUIRED;
        }

        if (!is_string($data['email'])) {
            return ConstantMessage::ONLY_STRING;
        }

        $email = $this->validateEmail->validMyEmail($data['email']);
        if ($email) {
            return $email;
        }

        $this->setEmail($data['email']);
        return null;
    }

    /**
     * @param array $data
     * @return string|null
     */
    private function _password(array $data)
    {
        if (!isset($data['password']))
        {
            return ConstantMessage::REQUIRED;
        }

        if (!is_string($data['password']))
        {
            return ConstantMessage::ONLY_STRING;
        }

        if (strlen($data['password']) < 8)
        {
            return ConstantMessage::MIN_REQUIRED;
        }

        $this->setPassword(Hash::make($data['password']));
        return null;
    }

    private function _age(array $data)
    {
        if (!isset($data['age']))
        {
            return ConstantMessage::REQUIRED;
        }

        if (!is_integer($data['age']) || $data['age'] < 0 )
        {
            return ConstantMessage::ONLY_INTEGER_POSITIVE;
        }

        $this->setAge($data['age']);
        return null;
    }

    private function _email_verified_at(array $data)
    {
        $today = date("Y-m-d H:i:s");
        $this->setEmail_verified_at($today);
        return null;
    }

    private function _permission()
    {
        $this->setPermissionId(2);
        return null;
    }
}
