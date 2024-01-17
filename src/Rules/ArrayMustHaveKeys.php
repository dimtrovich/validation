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

class ArrayMustHaveKeys extends AbstractRule
{
    protected $fillableParams = ['keys'];

    /**
     * {@inheritDoc}
     */
    public function fillParameters(array $params): Rule
    {
        return $this->fillAllowedParameters($params, 'keys');
    }

    public function keys(array $keys): static
    {
        $this->params['keys'] = $keys;

        return $this;
    }

    public function check(mixed $value): bool
    {
        $this->requireParameters(['keys']);

        if (! is_array($value)) {
            return false;
        }

        $ret = true;

        foreach ($this->parameter('keys') as $key) {
            $ret = $ret && array_key_exists($key, $value);
        }

        return $ret;
    }
}
