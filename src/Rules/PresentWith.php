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

class PresentWith extends AbstractRule
{
    /** @var bool */
    protected $implicit = true;

    /**
     * {@inheritDoc}
     */
    public function fillParameters(array $params): Rule
    {
        $this->params['fields'] = $params;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function check($value): bool
    {
        $this->requireParameters(['fields']);

        $fields = $this->parameter('fields');
        $this->setParameterTextValues($fields, 'values');

        $validator        = $this->validation->getValidator();
        $presentValidator = $validator('present');

        $presentValidator->setValidation($this->validation);
        $presentValidator->setAttribute($this->attribute);

        foreach ($fields as $field) {
            if ($this->validation->hasValue($field)) {
                return $presentValidator->check($value);
            }
        }

        return true;
    }
}
