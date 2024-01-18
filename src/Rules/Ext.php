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

use Dimtrovich\Validation\Traits\FileTrait;
use Rakit\Validation\Rule;

class Ext extends AbstractRule
{
    use FileTrait;
    
    protected array $parameters = [];

    /**
     * {@inheritDoc}
     */
    public function fillParameters(array $params): Rule
    {
        $this->parameters = $params;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function check($value): bool
    {
        if (! $this->isValidFileInstance($value)) {
            return false;
        }

        if ($this->shouldBlockPhpUpload($value, $this->parameters)) {
            return false;
        }

        return in_array(strtolower($value->clientExtension()), $this->parameters);
    }
}
