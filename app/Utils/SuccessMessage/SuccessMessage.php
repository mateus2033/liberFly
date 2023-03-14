<?php

namespace App\Utils\SuccessMessage;

class SuccessMessage
{
    /**
     * Return message error
     * @param string $message
     */
    public static function sucessMessage(string $message): array
    {
        return ['message' => $message];
    }
}