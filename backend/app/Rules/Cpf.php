<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Cpf implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $this->validateCpf($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'CPF Inválido';
    }

    private function validateCpf($cpf)
    {
        if (!function_exists('calcDigitsPositions')) {
            function calcDigitsPositions($digits, $positions = 10, $sumDigits = 0)
            {
                for ($i = 0; $i < strlen($digits); $i++) {
                    $sumDigits = $sumDigits + ($digits[$i] * $positions);
                    $positions--;
                }
                $sumDigits = $sumDigits % 11;
                if ($sumDigits < 2) {
                    $sumDigits = 0;
                } else {
                    $sumDigits = 11 - $sumDigits;
                }
                $cpf = $digits . $sumDigits;
                return $cpf;
            }
        }
        if (!$cpf) {
            return false;
        }
        $cpf = preg_replace('/[^0-9]/is', '', $cpf);
        if (strlen($cpf) != 11) {
            return false;
        }

        $digits = substr($cpf, 0, 9);
        $newCpf = calcDigitsPositions($digits);
        $newCpf = calcDigitsPositions($newCpf, 11);

        if ($newCpf === $cpf) {
            return true;
        } else {
            return false;
        }
    }
}
