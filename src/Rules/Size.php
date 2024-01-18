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

use Dimtrovich\Validation\Traits\FileTrait;
use Rakit\Validation\Rules\Traits\SizeTrait;

class Size extends AbstractRule
{
    use SizeTrait;
    use FileTrait;

    /**
     * @var array
     */
    protected $fillableParams = ['size'];

    /**
     * {@inheritDoc}
     */
    public function check($value): bool
    {
        $this->requireParameters($this->fillableParams);

        $this->setParameterText('size', $size = $this->parameter('size'));

        if ($this->isValidFileInstance($value)) {
            $valueSize = $value->getSize() / 1024;
        } else {
            $valueSize = $this->getValueSize($value);
        }

        return (float) $size === (float) $valueSize;
    }
}
