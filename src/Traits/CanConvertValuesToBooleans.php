<?php

/**
 * This file is part of Dimtrovich/Validation.
 *
 * (c) 2023 Dimitri Sitchet Tomkeu <devcode.dst@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Dimtrovich\Validation\Traits;

/**
 * @credit <a href="https://github.com/somnambulist-tech/validation">somnambulist-tech/validation - Somnambulist\Components\Validation\Rules\Behaviours\CanConvertValuesToBooleans</a>
 */
trait CanConvertValuesToBooleans
{
    private function convertStringsToBoolean(array $values): array
    {
        return array_map(function ($value) {
            if ($value === 'true') {
                return true;
            }
            if ($value === 'false') {
                return false;
            }

            return $value;
        }, $values);
    }

    private function convertBooleansToString(array $values): array
    {
        return array_map(function ($value) {
            if ($value === true) {
                return 'true';
            }
            if ($value === false) {
                return 'false';
            }

            return (string) $value;
        }, $values);
    }
}
