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

class Bic extends AbstractRule
{
    /**
     * Check if a given value is a valid Business Identifier Code (BIC).
     *
     * @see https://en.wikipedia.org/wiki/ISO_9362
     * @credit <a href="https://github.com/Intervention/validation">Intervention/validation - \Intervention\Validation\Rules\Bic</a>
     *
     * @param mixed $value
     */
    public function check($value): bool
    {
        return preg_match('/^[A-Za-z]{4} ?[A-Za-z]{2} ?[A-Za-z0-9]{2} ?([A-Za-z0-9]{3})?$/', $value) > 0;
    }
}
