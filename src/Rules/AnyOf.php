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

class AnyOf extends AbstractRule
{
    protected bool $strict = false;
    
    /**
     * {@inheritDoc}
     */
    public function fillParameters(array $params): Rule
    {
        return $this->fillAllowedValuesParameters($params);
    }

    public function values(array $values): static
    {
        $this->params['allowed_values'] = $values;

        return $this;
    }

    public function separator(string $char): static
    {
        $this->params['separator'] = $char;

        return $this;
    }

    public function strict(bool $strict = true): static
    {
        $this->strict = $strict;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function check($value): bool
    {
        $this->requireParameters(['allowed_values']);

        $valid  = true;
        $values = is_string($value) ? explode($this->parameter('separator', ','), $value) : (array)$value;

        foreach ($values as $v) {
            $valid = $valid && in_array($v, $this->parameter('allowed_values'), $this->strict);
        }

        return $valid;
    }
}
