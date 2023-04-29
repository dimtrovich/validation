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

class Image extends AbstractRule
{
    /**
     * @var string
     */
    protected $message = 'The :attribute must a valid image';

    /**
     * {@inheritDoc}
     */
    public function check($value): bool
    {
        return Rule::mimes(['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp'])->check($value);
    }
}
