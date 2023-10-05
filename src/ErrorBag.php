<?php

/**
 * This file is part of Dimtrovich/Validation.
 *
 * (c) 2023 Dimitri Sitchet Tomkeu <devcode.dst@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Dimtrovich\Validation;

use Rakit\Validation\ErrorBag as RakitErrorBag;

class ErrorBag extends RakitErrorBag
{
    /**
     * Returns key validation errors as a string
     */
    public function line(string $key, string $separator = ', ', string $format = ':message'): ?string
    {
        if ([] === $errors = $this->get($key, $format)) {
            return null;
        }

        return join($separator, $errors);
    }
}
