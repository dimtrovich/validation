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

class TypeArray extends AbstractRule
{
    /**
     * {@inheritDoc}
     */
    protected const NAME = 'array';

    /**
     * @var string
     */
    protected $message = 'The :attribute must be array';

    /**
     * {@inheritDoc}
     */
    public function fillParameters(array $params): Rule
    {
        return $this->fillAllowedParameters($params, 'allowed_keys');
    }

    /**
     * {@inheritDoc}
     */
    public function check($value): bool
    {
        if (! is_array($value)) {
            return false;
        }

        if (empty($parameters = $this->parameter('allowed_keys'))) {
            return true;
        }

        return empty(array_diff_key($value, array_fill_keys($parameters, '')));
    }
}
