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

use Dimtrovich\Validation\Rule;

class Time extends AbstractRule
{
    protected $fillableParams = ['mode'];

    /**
     * {@inheritDoc}
     */
    public function check($value): bool
    {
        $format = $this->parameter('mode') === 'strict' ? 'H:i:s' : 'H:i';

        return Rule::date($format)->check($value);
    }
}
