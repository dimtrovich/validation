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

class Semver extends AbstractRule
{
    /**
     * Check if the given value is a valid version numbers using Semantic Versioning.
     *
     * @see https://semver.org/
     * @credit <a href="https://github.com/Intervention/validation">Intervention/validation - \Intervention\Validation\Rules\Semver</a>
     *
     * @param mixed $value
     */
    public function check($value): bool
    {
        return preg_match('/^(0|[1-9]\d*)\.(0|[1-9]\d*)\.(0|[1-9]\d*)(?:-((?:0|[1-9]\d*|\d*[a-zA-Z-][0-9a-zA-Z-]*)(?:\.(?:0|[1-9]\d*|\d*[a-zA-Z-][0-9a-zA-Z-]*))*))?(?:\+([0-9a-zA-Z-]+(?:\.[0-9a-zA-Z-]+)*))?$/', $value);
    }
}
