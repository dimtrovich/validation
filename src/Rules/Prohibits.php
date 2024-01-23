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

use Rakit\Validation\Attribute;
use Rakit\Validation\Rule as RakitRule;

class Prohibits extends AbstractRule
{
    /**
     * {@inheritDoc}
     */
    public function fillParameters(array $params): RakitRule
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

        $parameters = $this->parameter('fields');

        $this->setParameterTextValues($parameters, 'other');

        $requiredValidator = $this->getRuleValidator('required');
        $requiredValidator->setAttribute($this->attribute);

        if ($requiredValidator->check($value)) {
            foreach ($parameters as $parameter) {
                $requiredValidator->setAttribute(new Attribute($this->validation, $parameter));
                if ($requiredValidator->check($this->validation->getValue($parameter))) {
                    return false;
                }
            }
        }

        return true;
    }
}
