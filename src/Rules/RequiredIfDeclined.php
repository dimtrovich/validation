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

use Dimtrovich\Validation\Rule;

class RequiredIfDeclined extends AbstractRule
{
    /**
     * @var bool
     */
    protected $implicit = true;

    /**
     * @var array
     */
    protected $fillableParams = ['field'];

    /**
     * {@inheritDoc}
     */
    public function check($value): bool
    {
        $this->requireParameters($this->fillableParams);

        $anotherAttribute = $this->parameter('field');
        $anotherValue     = $this->getAttribute()->getValue($anotherAttribute);

        $this->setParameterTextValues((array) $anotherAttribute, 'other_attribute');

        if (Rule::declined()->check($anotherValue)) {
            $requiredValidator = $this->getRuleValidator('required');

            $requiredValidator->setValidation($this->validation);
            $requiredValidator->setAttribute($this->attribute);

            return $requiredValidator->check($value);
        }

        return true;
    }
}
