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

class Fullname extends AbstractRule
{
    /**
     * Check if the given value is a valid fullname
     *
     * A string should represent a full name (at least 6 characters, at least 2 word, each word at least 2 characters long)
     *
     * @credit <a href="https://github.com/siriusphp/validation">siriusphp/validation - Sirius\Validation\Rule\FullName</a>
     *
     * This is not going to work with Asian names, http://en.wikipedia.org/wiki/Chinese_name.
     *
     * @param mixed $value
     */
    public function check($value): bool
    {
        $names = explode(' ', $value);

        // Each name must be at least 2 characters long.
        foreach ($names as $name) {
            if (mb_strlen($name) < 2) {
                return false;
            }
        }

        // Name cannot be longer shorter than 6 characters.
        return mb_strlen($value) >= 6;
    }
}
