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

use BlitzPHP\Utilities\Helpers;
use Dimtrovich\Validation\Utils\CountryPhoneCallback;

class Phone extends AbstractRule
{
    /**
     * @var array
     */
    protected $fillableParams = ['code'];

    /**
     * Check if a given value is valid phone number
     *
     * @credit <a href="https://github.com/milwad-dev/laravel-validate">milwad/laravel-validate - Milwad\LaravelValidate\Rules\ValidPhoneNumber</a>
     *
     * @param mixed $value
     */
    public function check($value): bool
    {
        if (! empty($code = $this->parameter('code'))) {
            $passes = (new CountryPhoneCallback($value, $code))->callPhoneValidator();

            return Helpers::collect($passes)->some(fn ($passe) => $passe);
        }

        return preg_match('/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/', $value);
    }
}
