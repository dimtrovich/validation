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

use Exception;
use Rakit\Validation\Rules\Traits\DateUtilsTrait;

class BeforeOrEqual extends AbstractRule
{
    use DateUtilsTrait;

    /**
     * @var string
     */
    protected $message = 'The :attribute must be a date before or equal :time.';

    /**
     * @var array
     */
    protected $fillableParams = ['time'];

    /**
     * Check the value is valid
     *
     * @param mixed $value
     *
     * @throws Exception
     */
    public function check($value): bool
    {
        $this->requireParameters($this->fillableParams);
        $time = $this->parameter('time');

        if (! $this->isValidDate($value)) {
            throw $this->throwException($value);
        }

        if (! $this->isValidDate($time)) {
            $time = $this->getAttribute()->getValue($time);
        }

        if (! $this->isValidDate($time)) {
            throw $this->throwException($time);
        }

        return $this->getTimeStamp($time) >= $this->getTimeStamp($value);
    }
}
