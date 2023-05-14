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

use Rakit\Validation\Rules\Traits\DateUtilsTrait;

class DateEquals extends AbstractRule
{
    use DateUtilsTrait;

    /**
     * @var array
     */
    protected $fillableParams = ['date'];

    /**
     * {@inheritDoc}
     */
    public function check($value): bool
    {
        $this->requireParameters($this->fillableParams);
        $time = $this->parameter('date');

        if (! $this->isValidDate($value)) {
            throw $this->throwException($value);
        }

        if (! $this->isValidDate($time)) {
            $time = $this->getAttribute()->getValue($time);
        }

        if (! $this->isValidDate($time)) {
            throw $this->throwException($time);
        }

        return $this->getTimeStamp($time) === $this->getTimeStamp($value);
    }
}
