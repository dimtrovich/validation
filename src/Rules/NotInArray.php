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

class NotInArray extends InArray
{
    /**
     * @var string
     */
    protected $message = 'The :attribute not allows :allowed_values';

    /**
     * {@inheritDoc}
     */
    public function check($value): bool
    {
        $this->requireParameters($this->fillableParams);

        $anotherValue = $this->validation->getValue($this->parameter('field'));
        $this->setAllowedValues($anotherValue, 'and');

        if (! is_array($anotherValue)) {
            return true;
        }

        return ! in_array($value, $anotherValue, $this->strict);
    }
}
