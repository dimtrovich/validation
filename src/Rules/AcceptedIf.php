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
use Rakit\Validation\Rule as RakitRule;

class AcceptedIf extends AbstractRule
{
    /**
     * @var string
     */
    protected $message = 'The :attribute must be accepted';

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

        if (in_array($anotherValue, $definedValues, true)) {
            return Rule::accepted()->check($value);
        }

        return true;
    }
}
