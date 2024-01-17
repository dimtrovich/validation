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

class TypeFloat extends AbstractRule
{
    /**
     * {@inheritDoc}
     */
    protected const NAME = 'float';

    public function check($value): bool
    {
        // https://www.php.net/manual/en/function.is-float.php#117304
        if (!is_scalar($value)) {
            return false;
        }

        if ('double' === gettype($value)) {
            return true;
        } else {
            return preg_match('/^\\d+\\.\\d+$/', (string)$value) === 1;
        }
    }
}
