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

class Slug extends AbstractRule
{
    /**
     * @var string
     */
    protected $message = 'The :attribute must be a valid slug';

    /**
     * {@inheritDoc}
     */
    public function check($value): bool
    {
        return is_string($value) && preg_match('/^([-a-z0-9_-])+$/i', $value);
    }
}
