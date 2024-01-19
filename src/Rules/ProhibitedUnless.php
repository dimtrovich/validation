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

use Dimtrovich\Validation\Traits\CanConvertValuesToBooleans;
use Rakit\Validation\Rule as RakitRule;

class ProhibitedUnless extends AbstractRule
{
    use CanConvertValuesToBooleans;

    protected $implicit = true;

    /**
     * {@inheritDoc}
     */
    public function fillParameters(array $params): RakitRule
    {
        $this->params['field']  = array_shift($params);
        $this->params['values'] = $this->convertStringsToBoolean($params);

        return $this;
    }

    public function field(string $field): static
    {
        $this->params['field'] = $field;

        return $this;
    }

    public function values(array $values): static
    {
        $this->params['values'] = $values;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function check($value): bool
    {
        $this->requireParameters(['field', 'values']);

        $anotherAttribute = $this->parameter('field');
        $definedValues    = $this->parameter('values');
        $anotherValue     = $this->getAttribute()->getValue($anotherAttribute);

        $this->setParameterTextValues($this->convertBooleansToString($definedValues), 'other');
        $this->setParameterTextValues([$anotherValue], 'values');

        $validator         = $this->validation->getValidator();
        $requiredValidator = $validator('required');

        if (! in_array($anotherValue, $definedValues, is_bool($anotherValue) || null === $anotherValue)) {
            return ! $requiredValidator->check($value);
        }

        return true;
    }
}
