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

class DoesntEndWith extends EndWith
{
    /**
     * @var string
     */
    protected $message = 'The :attribute must not end with :allowed_values';

    /**
     * {@inheritDoc}
     */
    public function check($value): bool
    {
        return ! parent::check($value);
    }
}
