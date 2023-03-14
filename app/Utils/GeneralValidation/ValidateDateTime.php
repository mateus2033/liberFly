<?php

namespace App\Utils\GeneralValidation;

use App\Utils\ConstantMessage\ConstantMessage;
use DateTime;

class ValidateDateTime {

    
    /** 
    * @param string $data
    * @return string
    */
   public static function validaData(string $data)
   {   
        
       $format   = 'd-m-Y';
       $response = DateTime::createFromFormat($format , $data);

       if(!is_bool($response))
       {
         $response = $response->format('Y-m-d');
         return $response;
       }
       
       return false;
   }

    /** 
    * @param string $data
    * @return string
    */
   public static function validaHora(string $hora)
   {
       $format    = 'H:i';
       $response  = DateTime::createFromFormat('!'. $format, $hora);
       $result    = $response && $response->format($format) === $hora;

       if(!$result)
       {
           return ConstantMessage::INVALID_TIME;
       }
       return $result;
   }
}