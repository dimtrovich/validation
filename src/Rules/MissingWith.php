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

use BlitzPHP\Utilities\Iterable\Arr;
use BlitzPHP\Utilities\Support\Invader;
use Rakit\Validation\Rule as RakitRule;

class MissingWith extends Missing
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

        $fields = $this->parameter('fields');
        $inputs = Invader::make($this->validation)->inputs;

        if (Arr::hasAny($inputs, $fields)) {
            return parent::check($value);
        }

        return true;
    }
}
