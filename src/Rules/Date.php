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

use DateTime;
use DateTimeInterface;
use Exception;

class Date extends AbstractRule
{
    /**
     * @var string
     */
    protected $message = 'The :attribute is not valid date';

    /**
     * @var array
     */
    protected $fillableParams = ['format'];

    /**
     * @var array
     */
    protected $params = [
        'format' => '',
    ];

    /**
     * {@inheritDoc}
     */
    public function check($value): bool
    {
        if ((! is_string($value) && ! is_numeric($value) && ! ($value instanceof DateTimeInterface))) {
            return false;
        }

        if (! empty($format = $this->parameter('format'))) {
            $this->message .= ' format';

            $date = DateTime::createFromFormat($format, $value);

            return $date && $date->format($format) === $value;

            // return date_create_from_format($format, $value) !== false;
        }

        if ($value instanceof DateTimeInterface) {
            return true;
        }

        try {
            if (strtotime($value) === false) {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }

        $date = date_parse($value);

        return checkdate($date['month'], $date['day'], $date['year']);
    }
}
