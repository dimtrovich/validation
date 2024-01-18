<?php

/**
 * This file is part of Dimtrovich/Validation.
 *
 * (c) 2023 Dimitri Sitchet Tomkeu <devcode.dst@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Dimtrovich\Validation\Traits;

use Countable;
use Rakit\Validation\Rules\Traits\SizeTrait as TraitsSizeTrait;

trait SizeTrait
{
    use TraitsSizeTrait;
    use FileTrait;

    /**
     * Get size (int) value from given $value
     *
     * @param mixed $value
     *
     * @return false|float
     */
    protected function getValueSize($value)
    {
        if (null !== $attribute = $this->getAttribute()) {
            if (is_numeric($value) && ($attribute->hasRule('integer') || $attribute->hasRule('numeric') || $attribute->hasRule('decimal'))) {
                $value = (float) $value;
            }
        }

        $size = match (true) {
            is_int($value) || is_float($value) => $value,
            is_string($value)                  => mb_strlen($value, 'UTF-8'),
            $this->isUploadedFileValue($value) => $value['size'],
            is_array($value)                   => count($value),
            $this->isValidFileInstance($value) => $value->getSize() / 1024,
            $value instanceof Countable        => $value->count(),
            default                            => false
        };

        return $size === false ? false : (float) $size;
    }
}
