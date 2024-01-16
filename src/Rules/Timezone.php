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

use BlitzPHP\Utilities\String\Text;
use DateTimeZone;

class Timezone extends AbstractRule
{
    protected $fillableParams = ['settings'];

    /**
     * {@inheritDoc}
     */
    public function check($value): bool
    {
        [$timezoneGroup, $countryCode] = explode(',', $this->parameter('settings', 'ALL')) + [1 => null];

        return in_array($value, timezone_identifiers_list(
            constant(DateTimeZone::class . '::' . Text::upper($timezoneGroup)),
            null !== $countryCode ? Text::upper($countryCode) : null,
        ), true);
    }
}
