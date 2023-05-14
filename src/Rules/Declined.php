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

class Declined extends AbstractRule
{
    /**
     * @var bool
     */
    protected $implicit = true;

    /**
     * {@inheritDoc}
     */
    public function check($value): bool
    {
        $acceptables = ['no', 'off', '0', 0, false, 'false'];

        return in_array($value, $acceptables, true);
    }
}
