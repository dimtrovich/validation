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

class InArray extends AbstractRule
{
    /**
     * @var string
     */
    protected $message = 'The :attribute only allows :allowed_values';

    /**
     * @var array
     */
    protected $fillableParams = ['field'];

    /**
     * @var bool
     */
    protected $strict = false;

    /**
     * Modifie le drapeau strict
     */
    public function strict(bool $strict = true): self
    {
        $this->strict = $strict;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function check($value): bool
    {
        $this->requireParameters($this->fillableParams);

        $anotherValue = $this->validation->getValue($this->parameter('field'));
        $this->setAllowedValues($anotherValue, 'or');

        if (! is_array($anotherValue)) {
            return false;
        }

        return in_array($value, $anotherValue, $this->strict);
    }
}
