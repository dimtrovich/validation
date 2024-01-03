<?php

/**
 * This file is part of Dimtrovich/Validation.
 *
 * (c) 2023 Dimitri Sitchet Tomkeu <devcode.dst@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Dimtrovich\Validation\Rules;

class CreditCard extends AbstractRule
{
    /**
     * Check if the credit card number is valid using the Luhn algorithm.
     *
     * @credit <a href="https://github.com/milwad-dev/laravel-validate">milwad/laravel-validate - Milwad\LaravelValidate\Rules\ValidCreditCard</a>
     *
     * @param mixed $value
     */
    public function check($value): bool
    {
        $value = preg_replace('/\D/', '', $value);

        $numLength = strlen($value);
        $sum = 0;
        $reverse = strrev($value);

        for ($i = 0; $i < $numLength; $i++) {
            $currentNum = intval($reverse[$i]);
            if ($i % 2 == 1) {
                $currentNum *= 2;
                if ($currentNum > 9) {
                    $currentNum -= 9;
                }
            }
            $sum += $currentNum;
        }

        return $sum % 10 == 0;
    }
}
