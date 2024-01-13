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

class Htmlclean extends AbstractRule
{
    /**
     * Check if a given value is free of any html code.
     *
     * @credit <a href="https://github.com/Intervention/validation">Intervention/validation - \Intervention\Validation\Rules\Htmlclean</a>
     * 
     * @param mixed $value
     */
    public function check($value): bool
    {
        return (strip_tags($value) == $value);
    }
}
