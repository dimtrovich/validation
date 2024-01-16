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

    /**
     * Translation locale
     */
    protected string $locale = 'en';

    /**
     * Aliases for attributes keys
     *
     * @var array<string, string>
     */
    private array $_alias = [];

    public function __construct()
    {
        $this->findTranslatedMessage($this->locale);
    }

    public function locale(string $locale): self
    {
        if ($locale !== $this->locale) {
            $this->findTranslatedMessage($locale);
        }

        return $this;
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

    protected function getAttributeAlias(string $key): string
    {
        if (! empty($this->_alias[$key])) {
            return $this->_alias[$key];
        }

        $alias = $this->validation->getAlias($key);

        return $this->_alias[$key] = empty($alias) ? $key : $alias;
    }

    

    /**
     * {@inheritDoc}
     */
    public function parameter(string $key, mixed $default = null): mixed
    {
        if (null === $value = parent::parameter($key)) {
            $value = $default;
        }

        return $value;
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
        $this->setParameterTextValues($values, 'allowed_values', $boolean);
    }

    protected function setParameterTextValues(mixed $values, string $name, string $boolean = 'or'): void
    {
        $values            = (array) $values;
        $boolean           = $this->validation ? $this->validation->getTranslation($boolean) : $boolean;
        $allowedValuesText = Helper::join(Helper::wraps($values, "'"), ', ', ", {$boolean} ");
        $this->setParameterText($name, $allowedValuesText);
    }

    private function findTranslatedMessage($locale)
    {
        $this->locale = $locale;

        if ($this->message === 'The :attribute is invalid') {
            if (null !== $translation = $this->translate(static::name(), true)) {
                $this->setMessage($translation);
            }
        }
    }
}
