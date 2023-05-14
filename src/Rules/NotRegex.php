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

class NotRegex extends AbstractRule
{
    /**
     * @var array
     */
    protected $fillableParams = ['regex'];

    /**
     * {@inheritDoc}
     */
    public function check($value): bool
    {
        if (! is_string($value) && ! is_numeric($value)) {
            return false;
        }

        $this->requireParameters($this->fillableParams);

        return preg_match($this->parameter('regex'), $value) < 1;
    }
}
