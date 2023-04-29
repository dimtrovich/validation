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

class AlphaDash extends AbstractRule
{
    /**
     * @var string
     */
    protected $message = 'The :attribute only allows a-z, 0-9, _ and -';

    protected $fillableParams = ['mode'];

    /**
     * {@inheritDoc}
     */
    public function check($value): bool
    {
        if (! is_string($value) && ! is_numeric($value)) {
            return false;
        }

        if ($this->parameter('mode') === 'ascii') {
            return preg_match('/\A[a-zA-Z0-9_-]+\z/u', $value) > 0;
        }

        return preg_match('/\A[\pL\pM\pN_-]+\z/u', $value) > 0;
    }
}
