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

class Isbn extends Ean
{
    /**
     * {@inheritDoc}
     */
    public function fillParameters(array $params): Rule
    {
        if (empty($params)) {
            $params = [10, 13];
        }

        return parent::fillParameters($params);
    }

    /**
     * Check if the current value is a valid International Standard Book Number (ISBN).
     *
     * @see https://en.wikipedia.org/wiki/International_Standard_Book_Number
     *
     * @credit <a href="https://github.com/Intervention/validation">Intervention/validation - \Intervention\Validation\Rules\Isbn</a>
     *
     * @param mixed $value
     */
    public function check($value): bool
    {
        // normalize value
        $value = preg_replace('/[^0-9x]/i', '', $value);

        if (! $this->hasAllowedLength($value)) {
            return false;
        }

        switch (strlen($value)) {
            case 10:
                return $this->shortChecksumMatches($value);

            case 13: // isbn-13 is a subset of ean-13
                return preg_match('/^(978|979)/', $value) && parent::checksumMatches($value);
        }

        return false;
    }

    /**
     * Determine if checksum for ISBN-10 numbers is valid
     */
    private function shortChecksumMatches(string $value): bool
    {
        return $this->getShortChecksum($value) % 11 === 0;
    }

    /**
     * Calculate checksum of short ISBN numbers
     */
    private function getShortChecksum(string $value): int
    {
        $checksum   = 0;
        $multiplier = 10;

        foreach (str_split($value) as $digit) {
            $digit = strtolower($digit) === 'x' ? 10 : (int) $digit;
            $checksum += $digit * $multiplier;
            $multiplier--;
        }

        return $checksum;
    }
}
