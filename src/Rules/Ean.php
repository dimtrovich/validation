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

class Ean extends AbstractRule
{
    protected array $lengths = [];

    /**
     * {@inheritDoc}
     */
    public function fillParameters(array $params): Rule
    {
        if (empty($params)) {
            $params = [8, 13];
        }

        $this->lengths = $params;

        return $this;
    }

    /**
     * Check if the current value has the lenghts of EAN-8 or EAN-13
     *
     * @credit <a href="https://github.com/Intervention/validation">Intervention/validation - \Intervention\Validation\Rules\Ean</a>
     *
     * @param mixed $value
     */
    public function check($value): bool
    {
        return is_numeric($value) && $this->hasAllowedLength($value) && $this->checksumMatches($value);
    }

    /**
     * Determine if the current value has the lenghts of EAN-8 or EAN-13
     */
    public function hasAllowedLength(string $value): bool
    {
        return in_array(strlen($value), $this->lengths, false);
    }

    /**
     * Try to calculate the EAN checksum of the current value and check the matching.
     */
    protected function checksumMatches(string $value): bool
    {
        return $this->calculateChecksum($value) === $this->cutChecksum($value);
    }

    /**
     * Cut out the checksum of the current value and return
     */
    protected function cutChecksum(string $value): int
    {
        return (int) (substr($value, -1));
    }

    /**
     * Calculate modulo checksum of given value
     */
    protected function calculateChecksum(string $value): int
    {
        $checksum = 0;

        // chars without check digit in reverse
        $chars = array_reverse(str_split(substr($value, 0, -1)));

        foreach ($chars as $key => $char) {
            $multiplier = $key % 2 ? 1 : 3;
            $checksum += (int) $char * $multiplier;
        }

        $remainder = $checksum % 10;

        if ($remainder === 0) {
            return 0;
        }

        return 10 - $remainder;
    }
}
