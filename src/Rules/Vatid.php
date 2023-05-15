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

class Vatid extends AbstractRule
{
    /**
     * Check if the given value is a valid VAT ID
     *
     * @credit <a href="https://github.com/milwad-dev/laravel-validate">milwad/laravel-validate - Milwad\LaravelValidate\Rules\ValidVatId</a>
     *
     * @param mixed $value
     */
    public function check($value): bool
    {
        // Remove all characters except letters and numbers
        $value = preg_replace('/[^a-zA-Z0-9]]/', '', $value);

        return preg_match('/[a-zA-Z]{2}[0-9]{0,12}$/', $value);
    }
}
