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

class Confirmed extends AbstractRule
{
    /**
     * {@inheritDoc}
     */
    public function check($value): bool
    {
        $anotherAttribute = $this->getAttribute()->getKey() . '_confirmation';
        $anotherValue     = $this->getAttribute()->getValue($anotherAttribute);

        $this->setParameterText('field', $this->getAttributeAlias($anotherAttribute));

        return $value === $anotherValue;
    }
}
