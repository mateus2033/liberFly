<?php
namespace App\Utils\ErroMensage;



class ErroMensage
{
    /**
     * Return message error
     * @param string $message
     */
    public static function errorMessage(string $message): array
    {
        return ['error' => $message];
    }

    /**
     * Return multiple message of erros
     * @param array $message
     */
    public static function errorMultipleMessage(array $message): array
    {
        return $message;
    }

}
