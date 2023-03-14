<?php

namespace App\Utils\GeneralValidation;

use App\Utils\ConstantMessage\ConstantMessage;

class ValidateCep {


    /**
     * @param string $cep
     * @return string|bool
     */
    public function validateCEP(string $cep) 
    {
        if(!preg_match('/^[0-9]{5,5}([- ]?[0-9]{3,3})?$/', $cep)) 
        {
            return ConstantMessage::INVALID_CEP;
        }
        return true;
    }
}

