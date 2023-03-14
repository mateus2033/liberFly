<?php

namespace App\Utils\GeneralValidation;

use Illuminate\Support\Facades\DB;
use App\Utils\ConstantMessage\ConstantMessage;

class ValidateEmail  {

    
    public function validMyEmail(string $email)
    {
        if(filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $self = new self();
            return $self->getEmail($email);
        } else {
            return ConstantMessage::INVALID_EMAIL;
        }
    } 

    public function getEmail(string $email)
    {
        $email = DB::table('users')->where('email' ,'=', $email)->first();
        if(!is_null($email))
        {
            return ConstantMessage::RESGISTRED_EMAIL;
        }

        return null;
    }
}