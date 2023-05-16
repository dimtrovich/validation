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
use Dimtrovich\Validation\Utils\Translator;
use Rakit\Validation\Helper;
use Rakit\Validation\Rule;

abstract class AbstractRule extends Rule
{
    /**
     * Validation rule name
     */
    protected const NAME = '';

    public function __construct(protected string $locale)
    {
        if ($this->message === 'The :attribute is invalid') {
            if (null !== $translation = $this->translate(static::name(), true)) {
                $this->setMessage($translation);
            }
        }
    }

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

        return Text::snake($name);
    }

    /**
     * Get the translation for a given key
     */
    protected function translate(?string $key, bool $strict = false): ?string
    {
        return Translator::translate($key ?: static::name(), $this->locale, $strict);
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
