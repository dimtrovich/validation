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

class Alpha extends AbstractRule
{
    /**
     * @var string
     */
    protected $message = 'The :attribute only allows alphabet characters';

    protected $fillableParams = ['mode'];

    /**
     * {@inheritDoc}
     */
    public function check($value): bool
    {
        if ($this->parameter('mode') === 'ascii') {
            return is_string($value) && preg_match('/\A[a-zA-Z]+\z/u', $value);
        }

        return is_string($value) && preg_match('/\A[\pL\pM]+\z/u', $value);
    }
}
