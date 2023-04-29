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

class DoesntStartWith extends StartWith
{
    /**
     * @var string
     */
    protected $message = 'The :attribute must not start with :allowed_values';

    /**
     * {@inheritDoc}
     */
    public function check($value): bool
    {
        return ! parent::check($value);
    }
}
