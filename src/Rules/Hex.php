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

class Hex extends AbstractRule
{
    /**
     * Check if a given value is a valid hexadecimal string.
     *
     * @credit <a href="https://github.com/aplus-framework/validation/blob/master/src/Validator.php#L158">aplus-framework/validation</a>
     *
     * @param mixed $value
     */
    public function check($value): bool
    {
        return $value !== null && \ctype_xdigit($value);
    }
}
