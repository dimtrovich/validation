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
use TypeError;

class Enum extends AbstractRule
{
    /**
     * @param string $type Type de l'enumeration
     */
    public function __construct(protected string $type = '')
    {
        $this->type = $type;
    }

    /**
     * {@inheritDoc}
     */
    public function fillParameters(array $params): Rule
    {
        if (count($params) === 1 && is_string($params[0])) {
            $this->type = $params[0];
        }

        return $this;
    }

    /**
     * Modifie le type de l'enumeration
     */
    public function type(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function check($value): bool
    {
        if ($value instanceof $this->type) {
            return true;
        }

        if (empty($value) || ! function_exists('enum_exists') || ! enum_exists($this->type) || ! method_exists($this->type, 'tryFrom')) {
            return false;
        }

        try {
            return null !== $this->type::tryFrom($value);
        } catch (TypeError $e) {
            return false;
        }
    }
}
