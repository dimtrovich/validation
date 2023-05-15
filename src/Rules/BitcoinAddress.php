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

class BitcoinAddress extends AbstractRule
{
    /**
     * Check if the given value is a valid bitcoin address
     *
     * @credit <a href="https://github.com/milwad-dev/laravel-validate">milwad/laravel-validate - Milwad\LaravelValidate\Rules\ValidBitcoinAddress</a>
     *
     * @param mixed $value
     */
    public function check($value): bool
    {
        return preg_match('/^(bc1|[13])[a-zA-HJ-NP-Z0-9]{25,39}$/', $value);
    }
}
