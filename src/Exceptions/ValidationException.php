<?php

/**
 * This file is part of Dimtrovich/Validation.
 *
 * (c) 2023 Dimitri Sitchet Tomkeu <devcode.dst@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Dimtrovich\Validation\Exceptions;

use Dimtrovich\Validation\Validation;
use Exception;

class ValidationException extends Exception
{
    /**
     * Create an error message summary from the validation errors.
     */
    public static function summarize(Validation $validator): string
    {
        $messages = $validator->errors()->all();

        if (! count($messages) || ! is_string($messages[0])) {
            return $validator->getValidator()->getTranslation('The given data was invalid.');
        }

        $message = array_shift($messages);

        if ($count = count($messages)) {
            $pluralized = $count === 1 ? 'error' : 'errors';

            $message .= ' ' . $validator->getValidator()->getTranslation("(and {$count} more {$pluralized})");
        }

        return $message;
    }

    public static function ruleNotFound(?string $rule = null)
    {
        return new self($rule . ' is not a valid rule.');
    }
}
