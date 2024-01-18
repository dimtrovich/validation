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

use Dimtrovich\Validation\Traits\SizeTrait;

class Min extends AbstractRule
{
    use SizeTrait;

    /** @var array */
    protected $fillableParams = ['min'];

    /**
     * {@inheritDoc}
     */
    public function check($value): bool
    {
        $this->requireParameters($this->fillableParams);

        $min       = $this->getBytesSize($this->parameter('min'));
        $valueSize = $this->getValueSize($value);
        
        if (!is_numeric($valueSize)) {
            return false;
        }

        return $valueSize >= $min;
    }
}
