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

class Length extends AbstractRule
{
    protected $fillableParams = ['length'];

    public function check(mixed $value): bool
    {
        $this->requireParameters($this->fillableParams);

        return (int)$this->parameter('length') === mb_strlen((string)$value);
    }
}
