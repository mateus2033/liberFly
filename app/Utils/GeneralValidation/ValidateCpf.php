<?php

namespace App\Utils\GeneralValidation;

use App\Utils\ConstantMessage\ConstantMessage;
use Illuminate\Support\Facades\DB;

class ValidateCpf
{
    /**
    * @param string $cpf
    * @return $cpf|string
    */
    public function validarCPF(string $cpf)
    {
        $cpf = preg_replace('/[^0-9]/is', '', $cpf);
        if (strlen($cpf) != 11) {
            return ConstantMessage::INVALID_CPF;
        }

        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return ConstantMessage::INVALID_CPF;
        }

        for ($t = 9; $t < 11; $t++) {

            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return ConstantMessage::INVALID_CPF;
            }
        }

        return $this->getCpf($cpf);
    }

    private function getCpf(string $cpf)
    {
        $response = DB::table('users')->where('cpf', '=', $cpf)->first();
        if(!is_null($response))
        {
            return ConstantMessage::REGISTRED_CPF;
        }
        return null;
    }
}
