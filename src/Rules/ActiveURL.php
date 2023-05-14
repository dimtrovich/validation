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

use Exception;

class ActiveURL extends AbstractRule
{
    /**
     * {@inheritDoc}
     */
    protected const NAME = 'active_url';

    /**
     * @var bool
     */
    protected $implicit = true;

    /**
     * {@inheritDoc}
     */
    public function check($value): bool
    {
        if (! is_string($value)) {
            return false;
        }

        if ($url = parse_url($value, PHP_URL_HOST)) {
            try {
                $records = dns_get_record($url . '.', DNS_A | DNS_AAAA);

                if (is_array($records) && count($records) > 0) {
                    return true;
                }
            } catch (Exception $e) {
                return false;
            }
        }

        return false;
    }
}
