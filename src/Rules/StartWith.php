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

use BlitzPHP\Utilities\String\Text;
use Rakit\Validation\Rule;

class StartWith extends AbstractRule
{
    /**
     * @var string
     */
    protected $message = 'The :attribute must start with :allowed_values';

    /**
     * {@inheritDoc}
     */
    public function fillParameters(array $params): Rule
    {
        return $this->fillAllowedValuesParameters($params);
    }

    /**
     * {@inheritDoc}
     */
    public function check($value): bool
    {
        $this->requireParameters(['allowed_values']);

        $this->setAllowedValues($allowedValues = $this->parameter('allowed_values'));

        return Text::startsWith($value, $allowedValues);
    }
}
