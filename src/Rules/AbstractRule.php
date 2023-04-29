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
use Rakit\Validation\Helper;
use Rakit\Validation\Rule;

abstract class AbstractRule extends Rule
{
    /**
     * Validation rule name
     */
    protected const NAME = '';

    /**
     * Get the name of the validation rule
     */
    public static function name(): string
    {
        if ('' !== static::NAME) {
            return static::NAME;
        }

        $parts = explode('\\', static::class);
        $name  = array_pop($parts);

        return Text::convertTo($name, 'snake');
    }

    protected function getRuleValidator(string $rule): Rule
    {
        $validator = $this->validation->getValidator();

        return $validator->getValidator($rule);
    }

    protected function fillAllowedParameters(array $params, string $name): self
    {
        if (count($params) === 1 && is_array($params[0])) {
            $params = $params[0];
        }
        $this->params[$name] = $params;

        return $this;
    }

    protected function fillAllowedValuesParameters(array $params): self
    {
        return $this->fillAllowedParameters($params, 'allowed_values');
    }

    protected function setAllowedValues(array $values, string $boolean = 'or'): void
    {
        $boolean           = $this->validation ? $this->validation->getTranslation($boolean) : $boolean;
        $allowedValuesText = Helper::join(Helper::wraps($values, "'"), ', ', ", {$boolean} ");
        $this->setParameterText('allowed_values', $allowedValuesText);
    }
}
