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

class Cidr extends AbstractRule
{
    /**
     * Check if the value is a Classless Inter-Domain Routing notation (CIDR).
     *
     * @see https://en.wikipedia.org/wiki/Classless_Inter-Domain_Routing
     * @credit <a href="https://github.com/Intervention/validation">Intervention/validation - \Intervention\Validation\Rules\Cidr</a>
     *
     * @param mixed $value
     */
    public function check($value): bool
    {
        // A CIDR should consist of an IP part and a mask bit number delimited by a `/`

        // split by the slash that should be there
        $parts = explode('/', $value, 2);
        // we should have 2 parts (the ip and mask)
        if (count($parts) !== 2) {
            return false;
        }

        // validate the ip part
        if (filter_var($parts[0], FILTER_VALIDATE_IP) === false) {
            return false;
        }

        // check the mask part
        // first of all, this should be an integer
        if (filter_var($parts[1], FILTER_VALIDATE_INT) === false) {
            return false;
        }

        // and it should be between 0 and 32 inclusive
        return ! ($parts[1] < 0 || $parts[1] > 32);
    }
}
