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

use BlitzPHP\Utilities\String\Text;

class Uuid extends AbstractRule
{
    /**
     * @var string
     */
    protected $message = 'The :attribute a valid Uuid';

    /**
     * {@inheritDoc}
     */
    public function check($value): bool
    {
        return Text::isUuid($value);
    }
}
