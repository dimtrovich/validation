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

use BlitzPHP\Utilities\Support\Invader;
use Rakit\Validation\Rule;

class Distinct extends AbstractRule
{
    protected array $parameters = [];

    /**
     * {@inheritDoc}
     */
    public function fillParameters(array $params): Rule
    {
        $this->parameters = $params;

        return $this;
    }

    public function ignoreCase(bool $bool = true): static
    {
        return $this->_setParams('ignore_case', $bool);
    }
    
    public function strict(bool $bool = true): static
    {
        return $this->_setParams('strict', $bool);
    }

    /**
     * {@inheritDoc}
     */
    public function check($value): bool
    {
        if (! is_array($value)) {
            if (null === $attribute  = $this->attribute->getPrimaryAttribute()) {
                return false;
            }

            $value = array_map(
                fn($attribute) => $attribute->getValue(),
                Invader::make($this->validation)->parseArrayAttribute($attribute)
            );
        }

        if (in_array('ignore_case', $this->parameters, true)) {
            $value = array_map(function($v) {
                if (is_string($v)) {
                    $v = strtolower($v);
                }

                return $v;
            }, $value);
        }

        return $value === array_unique($value, SORT_REGULAR);
    }    

    private function _setParams(string $param, bool $bool = true): static
    {
        if ($bool) {
            $this->parameters[] = $param;
            $this->parameters   = array_unique($this->parameters);
        } else {
            $this->parameters = array_filter($this->parameters, fn($value) => $value !== $param);
        }

        return $this;
    }
}
