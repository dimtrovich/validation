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

class Email extends AbstractRule
{
    /**
     * @var array
     */
    protected $fillableParams = ['mode'];

    /**
     * {@inheritDoc}
     */
    public function check($value): bool
    {
        if (!is_string($value)) {
            return false;
        }

        if (filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
            return false;
        }
        
        if ($this->parameter('mode') === 'dns') {
            $domain = ltrim(stristr($value, '@'), '@') . '.';
            if (function_exists('idn_to_ascii') && defined('INTL_IDNA_VARIANT_UTS46')) {
                $domain = idn_to_ascii($domain, 0, INTL_IDNA_VARIANT_UTS46);
            }

            return checkdnsrr($domain, 'MX');
        }

        return true;
    }
}
