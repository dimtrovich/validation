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

class Imei extends AbstractRule
{
    /**
     * Check if a given value is a valid IMEI.
     *
     * @credit <a href="https://github.com/milwad-dev/laravel-validate">milwad/laravel-validate - Milwad\LaravelValidate\Rules\ValidImei</a>
     *
     * @param mixed $value
     */
    public function check($value): bool
    {
        $imei = $value;

        if (strlen($imei) !== 15 || ! ctype_digit($imei)) {
            return false;
        }

        $digits    = str_split($imei); // Get digits
        $imei_last = array_pop($digits); // Remove last digit, and store it
        $log       = [];

        foreach ($digits as $key => $n) {
            if ($key & 1) {
                $double = str_split($n * 2); // Get double digits
                $n      = array_sum($double); // Sum double digits
            }

            $log[] = $n; // Append log
        }
        $sum = array_sum($log) * 9; // Sum log & multiply by 9

        return substr($sum, -1) === $imei_last;
    }
}
