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

use Rakit\Validation\Rule;

class Gtin extends Ean
{
    /**
     * {@inheritDoc}
     */
    public function fillParameters(array $params): Rule
    {
        if (empty($params)) {
            $params = [8, 12, 13, 14];
        }

        return parent::fillParameters($params);
    }

    /**
     * Check if the current value is a valid Global Trade Item Number.
     *
     * Value must be either GTIN-13 or GTIN-8, which is checked as EAN by parent class. 
     * Or value must be GTIN-14 or GTIN-12 which will be handled like this:
     *
     * - GTIN-14 will be checked as EAN-13 after cropping first char
     * - GTIN-12 will be checked as EAN-13 after adding leading zero
     * 
     * @credit <a href="https://github.com/Intervention/validation">Intervention/validation - \Intervention\Validation\Rules\Gtin</a>
     *
     * @param mixed $value
     */
    public function check($value): bool
    {
        if (!is_numeric($value)) {
            return false;
        }

        if (!$this->hasAllowedLength($value)) {
            return false;
        }

        switch (strlen($value)) {
            case 8:
            case 13:
                return parent::check($value);

            case 14:
                return parent::checksumMatches(substr($value, 1));

            case 12:
                return parent::checksumMatches('0' . $value);
        }

        return false;
    }
}
