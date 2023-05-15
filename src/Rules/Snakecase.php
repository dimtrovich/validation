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

class Snakecase extends AbstractRule
{
    /**
     * Check if the given value is a snake case string
     *
     * @credit <a href="https://github.com/milwad-dev/laravel-validate">milwad/laravel-validate - Milwad\LaravelValidate\Rules\ValidSnakeCase</a>
     *
     * @param mixed $value
     */
    public function check($value): bool
    {
        return preg_match('/^(?:\p{Ll}+_)*\p{Ll}+$/u', $value);
    }
}
