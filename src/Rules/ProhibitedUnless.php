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

use Rakit\Validation\Rule as RakitRule;

class ProhibitedUnless extends AbstractRule
{
    /**
     * {@inheritDoc}
     */
    public function fillParameters(array $params): RakitRule
    {
        $this->params['field']  = array_shift($params);
        $this->params['values'] = $params;
        
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

        $this->setParameterTextValues($definedValues, 'other');
        $this->setParameterTextValues([$anotherValue], 'values');

        $validator         = $this->validation->getValidator();
        $requiredValidator = $validator('required');

        if (! in_array($anotherValue, $definedValues, is_bool($anotherValue) || is_null($anotherValue))) {
            return ! $requiredValidator->check($value, []);
        }

        return true;
    }
}
