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

use BlitzPHP\Utilities\Iterable\Arr;
use Dimtrovich\Validation\Validator;

class Password extends AbstractRule
{
    /**
     * @var array
     */
    protected $fillableParams = ['min'];

    /**
     * The minimum size of the password.
     */
    protected int $min = 8;

    /**
     * If the password requires at least one uppercase and one lowercase letter.
     */
    protected bool $mixedCase = false;

    /**
     * If the password requires at least one letter.
     */
    protected bool $letters = false;

    /**
     * If the password requires at least one number.
     */
    protected bool $numbers = false;

    /**
     * If the password requires at least one symbol.
     */
    protected bool $symbols = false;

    /**
     * If the password should not have been compromised in data leaks.
     */
    protected bool $uncompromised = false;

    /**
     * The number of times a password can appear in data leaks before being considered compromised.
     */
    protected int $compromisedThreshold = 0;

    /**
     * Additional validation rules that should be merged into the default rules during validation.
     */
    protected array $customRules = [];

    /**
     * The callback that will generate the "default" version of the password rule.
     *
     * @var array|callable|string|null
     */
    public static $defaultCallback;

    /**
     * Get the default configuration of the password rule.
     */
    public function default(): self
    {
        $password = is_callable(static::$defaultCallback)
                            ? call_user_func(static::$defaultCallback)
                            : static::$defaultCallback;

        return $password instanceof self ? $password : $this->min(8);
    }

    /**
     * Get configuration for the strong password
     */
    public function strong(): self
    {
        return (clone $this)->min(8)->symbols()->letters()->numbers()->mixedCase();
    }

    /**
     * Sets the minimum size of the password.
     */
    public function min(int $size): self
    {
        $this->min = $size;

        return $this;
    }

    /**
     * Ensures the password has not been compromised in data leaks.
     */
    public function uncompromised(int $threshold = 0): self
    {
        $this->uncompromised = true;

        $this->compromisedThreshold = $threshold;

        return $this;
    }

    /**
     * Makes the password require at least one uppercase and one lowercase letter.
     */
    public function mixedCase(): self
    {
        $this->mixedCase = true;

        return $this;
    }

    /**
     * Makes the password require at least one letter.
     */
    public function letters(): self
    {
        $this->letters = true;

        return $this;
    }

    /**
     * Makes the password require at least one number.
     */
    public function numbers(): self
    {
        $this->numbers = true;

        return $this;
    }

    /**
     * Makes the password require at least one symbol.
     */
    public function symbols(): self
    {
        $this->symbols = true;

        return $this;
    }

    /**
     * Specify additional validation rules that should be merged with the default rules during validation.
     */
    public function rules(array|string $rules): self
    {
        $this->customRules = Arr::wrap($rules);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function check($value): bool
    {
        if (! is_string($value)) {
            return false;
        }

        if (is_numeric($this->params['min'] ?? '')) {
            $this->min = (int) $this->params['min'];
        }

        $attribute = $this->getAttribute()->getKey();

        $validator = Validator::make(
            [$attribute => $value],
            [$attribute => array_merge(['string', 'min:' . $this->min], $this->customRules)],
            $this->validation->getMessages(),
        );

        if ($this->mixedCase && ! preg_match('/(\p{Ll}+.*\p{Lu})|(\p{Lu}+.*\p{Ll})/u', $value)) {
            $this->validation->errors()->add($attribute, static::name() . '.mixed', $this->processTranslate('password.mixed'));
        }
        if ($this->letters && ! preg_match('/\pL/u', $value)) {
            $this->validation->errors()->add($attribute, static::name() . '.letters', $this->processTranslate('password.letters'));
        }
        if ($this->symbols && ! preg_match('/\p{Z}|\p{S}|\p{P}/u', $value)) {
            $this->validation->errors()->add($attribute, static::name() . '.symbols', $this->processTranslate('password.symbols'));
        }
        if ($this->numbers && ! preg_match('/\pN/u', $value)) {
            $this->validation->errors()->add($attribute, static::name() . '.numbers', $this->processTranslate('password.numbers'));
        }

        return $validator->setValidation($this->validation)->passes();
    }

    private function processTranslate(string $key): string
    {
        return str_replace(
            ':attribute',
            $this->getAttributeAlias($this->getAttribute()->getKey()),
            $this->translate($key)
        );
    }
}
