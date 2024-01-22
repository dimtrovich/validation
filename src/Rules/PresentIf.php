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

use Rakit\Validation\Rule;

class PresentIf extends AbstractRule
{
    /**
     * @var bool
     */
    protected $implicit = true;

    /**
     * {@inheritDoc}
     */
    public function fillParameters(array $params): Rule
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

        $this->setParameterTextValues((array) $anotherAttribute, 'other_attribute');
        $this->setParameterTextValues((array) $definedValues, 'other_value');

        if (in_array($anotherValue, $definedValues, is_bool($anotherValue) || null === $definedValues)) {
            $validator        = $this->validation->getValidator();
            $presentValidator = $validator('present');

            $presentValidator->setValidation($this->validation);
            $presentValidator->setAttribute($this->attribute);

            return $presentValidator->check($value);
        }

        return true;
    }
}
