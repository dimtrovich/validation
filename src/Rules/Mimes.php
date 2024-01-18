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
use Rakit\Validation\Rules\Mimes as RulesMimes;

class Mimes extends AbstractRule
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
            $rule = new RulesMimes();
            $rule->setAttribute($this->getAttribute());
            $rule->setValidation($this->validation);
            $rule->setKey($this->getKey());
            $rule->setParameters($this->getParameters());
            $rule->setMessage($this->getMessage());
            $rule->allowTypes($this->parameters);
            
            return $rule->check($value);
        }

        if ($this->shouldBlockPhpUpload($value, $this->parameters)) {
            return false;
        }

        return $value->getPath() !== '' && in_array($value->guessExtension(), $this->parameters);
    }
}
