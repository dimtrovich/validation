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

use Dimtrovich\Validation\Exceptions\ValidationException;
use InvalidArgumentException;
use Rakit\Validation\Rule;

class Validator
{
    /**
     * Class to use for validation.
     * Useful if we have modified certain behaviors in the validation class (adding a new validator for example)
     *
     * @var class-string<Validation>
     */
    protected static string $validationClass = Validation::class;

    /**
     * Initializes the validation process
     */
    public static function make(array $data, array $rules, array $messages = []): Validation
    {
        self::ensureValidValidationClass();

        $instance = new static::$validationClass();

        $instance->data($data);
        $instance->rules($rules);
        $instance->messages($messages);

        return $instance;
    }

    /**
     * Create and return a validator on the fly
     *
     * @return Rule
     */
    public static function rule(string $rule)
    {
        self::ensureValidValidationClass();

        $args   = func_get_args();
        $rule   = array_shift($args);
        $params = $args;

        $validator = static::$validationClass::instance()->getValidator($rule);
        if (! ($validator instanceof Rule)) {
            throw ValidationException::ruleNotFound($rule);
        }

        $clonedValidator = clone $validator;
        $clonedValidator->fillParameters($params);

        return $clonedValidator;
    }

    /**
     * Make sure the class to use for validation is a subclass of Validation
     */
    private static function ensureValidValidationClass(): void
    {
        if (! is_a(static::$validationClass, Validation::class, true)) {
            throw new InvalidArgumentException('Static property $validationClass must be a subclass of ' . Validation::class);
        }
    }
}
