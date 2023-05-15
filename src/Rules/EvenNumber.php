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

class EvenNumber extends AbstractRule
{
    /**
     * Check if a given value is a even number.
     *
     * @credit <a href="https://github.com/milwad-dev/laravel-validate">milwad/laravel-validate - Milwad\LaravelValidate\Rules\ValidEvenNumber</a>
     *
     * @param mixed $value
     */
    public function check($value): bool
    {
        return preg_match('/^\d*[02468]$/', $value);
    }
}
