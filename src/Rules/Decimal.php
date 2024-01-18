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

class Decimal extends AbstractRule
{
    /**
     * @var array
     */
    protected $fillableParams = ['min', 'max'];

    /**
     * {@inheritDoc}
     */
    public function check($value): bool
    {
        if (! is_numeric($value)) {
            return false;
        }

        $this->requireParameters(['min']);

        $min = $this->parameter('min');
        $max = $this->parameter('max');

        $matches = [];

        if (preg_match('/^[+-]?\d*\.?(\d*)$/', $value, $matches) !== 1) {
            return false;
        }

        $decimals = strlen(end($matches));

        if (empty($max)) {
            return (int) $decimals === (int) $min;
        }

        return $decimals >= $min && $decimals <= $max;
    }
}
