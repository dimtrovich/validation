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

class Mimetypes extends AbstractRule
{
    use FileTrait;
    
    protected array $parameters = [];

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
        $this->setAllowedValues($this->parameters = $this->parameter('allowed_values'));
        
        if (! $this->isValidFileInstance($value)) {
            return false;
        }

        if ($this->shouldBlockPhpUpload($value, $this->parameters)) {
            return false;
        }

        return $value->getPath() !== '' &&
                (in_array($value->getMimeType(), $this->parameters) ||
                 in_array(explode('/', $value->getMimeType())[0].'/*', $this->parameters));
    }
}
