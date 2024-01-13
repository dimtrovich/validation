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

class MultipleOf extends AbstractRule
{
    /**
     * @var array
     */
    protected $fillableParams = ['value'];

    /**
     * {@inheritDoc}
     */
    public function check($value): bool
    {
        $this->requireParameters($this->fillableParams);

        if (is_int($value) || is_float($value)) {
            $value = (string) $value;
        }

        if (! is_string($value)) {
            return false;
        }

        if (! is_numeric($compare = $this->parameter('value'))) {
            $compare = $this->getAttribute()->getValue($compare);
        }

        if (! is_numeric($value) || ! is_numeric($compare) || ($compare === 0 && $value === 0)) {
            return false;
        }

        if ($value === 0) {
            return true;
        }

        if ($compare === 0) {
            return false;
        }

        return $value % $compare === 0;
    }
}
