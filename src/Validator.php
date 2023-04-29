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
use Rakit\Validation\Rule;

class Validator
{
    public static function make(array $data, array $rules, array $messages = []): Validation
    {
        $instance = new Validation();

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
        $args   = func_get_args();
        $rule   = array_shift($args);
        $params = $args;

        $validator = Validation::instance()->getValidator($rule);
        if (! ($validator instanceof Rule)) {
            throw ValidationException::ruleNotFound($rule);
        }

        $clonedValidator = clone $validator;
        $clonedValidator->fillParameters($params);

        return $clonedValidator;
    }
}
