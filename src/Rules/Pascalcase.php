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

class Pascalcase extends AbstractRule
{
    /**
     * Check if the given value is a pascal case string
     *
     * @credit <a href="https://github.com/milwad-dev/laravel-validate">milwad/laravel-validate - Milwad\LaravelValidate\Rules\ValidPascalCase</a>
     *
     * @param mixed $value
     */
    public function check($value): bool
    {
        return preg_match('/^[A-Z][a-z]+(?:[A-Z][a-z]+)*$/', $value);
    }
}
