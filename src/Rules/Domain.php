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

class Domain extends AbstractRule
{
    /**
     * Check if a given value is a valid domain name.
     *
     * @credit <a href="https://github.com/Intervention/validation">Intervention/validation - \Intervention\Validation\Rules\Domainname</a>
     *
     * @param mixed $value
     */
    public function check($value): bool
    {
        $labels = $this->getLabels($value); // get labels of domainname
        $tld    = end($labels); // most right label of domainname is tld

        // domain must have 2 labels minimum
        if (count($labels) <= 1) {
            return false;
        }

        // each label must be valid
        foreach ($labels as $label) {
            if (! $this->isValidLabel($label)) {
                return false;
            }
        }

        // tld must be valid
        return ! (! $this->isValidTld($tld));
    }

    /**
     * Get all labels of domainname
     */
    private function getLabels(mixed $value): array
    {
        return explode('.', $this->idnToAscii($value));
    }

    /**
     * Determine if given string is valid idn label
     */
    private function isValidLabel(string $value): bool
    {
        return $this->isValidALabel($value) || $this->isValidNrLdhLabel($value);
    }

    /**
     * Determine if given value is valid A-Label
     *
     * Begins with "xn--" and is resolvable by PunyCode algorithm
     */
    private function isValidALabel(string $value): bool
    {
        return substr($value, 0, 4) === 'xn--' && $this->idnToUtf8($value) !== false;
    }

    /**
     * Determine if given value is valud NR-LDH label
     */
    private function isValidNrLdhLabel(string $value): bool
    {
        return (bool) preg_match('/^(?!\\-)[a-z0-9\\-]{1,63}(?<!\\-)$/i', $value);
    }

    /**
     * Determine if given value is valid TLD
     */
    private function isValidTld(string $value): bool
    {
        if ($this->isValidALabel($value)) {
            return true;
        }

        return (bool) preg_match('/^[a-z]{2,63}$/i', $value);
    }

    /**
     * Wrapper method for idn_to_utf8 call
     */
    private function idnToUtf8(string $domain): string
    {
        return idn_to_utf8($domain, IDNA_DEFAULT, INTL_IDNA_VARIANT_UTS46);
    }

    /**
     * Wrapper method for idn_to_ascii call
     */
    private function idnToAscii(string $domain): string
    {
        return idn_to_ascii($domain, IDNA_DEFAULT, INTL_IDNA_VARIANT_UTS46);
    }
}
