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

class Issn extends AbstractRule
{
    /**
     * Check if the current value is a valid International Standard Serial Number (ISSN).
     *
     * @see https://en.wikipedia.org/wiki/International_Standard_Serial_Number
     *
     * @credit <a href="https://github.com/Intervention/validation">Intervention/validation - \Intervention\Validation\Rules\Issn</a>
     *
     * @param mixed $value
     */
    public function check($value): bool
    {
        return preg_match('/^[0-9]{4}-[0-9]{3}[0-9xX]$/', $value) && $this->checkSumMatches($value);
    }

    /**
     * Determine if checksum matches
     */
    private function checkSumMatches(string $value): bool
    {
        return $this->calculateChecksum($value) === $this->parseChecksum($value);
    }

    /**
     * Calculate checksum from the current value
     */
    private function calculateChecksum(string $value): int
    {
        $checksum     = 0;
        $issn_numbers = str_replace('-', '', $value);

        foreach (range(8, 2) as $num => $multiplicator) {
            $checksum += (int) ($issn_numbers[$num]) * $multiplicator;
        }

        $remainder = ($checksum % 11);

        return $remainder === 0 ? 0 : 11 - $remainder;
    }

    /**
     * Parse attached checksum of current value (last digit)
     */
    private function parseChecksum(string $value): int
    {
        $last = substr($value, -1);

        return strtolower($last) === 'x' ? 10 : (int) $last;
    }
}
