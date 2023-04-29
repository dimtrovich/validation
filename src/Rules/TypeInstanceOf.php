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

class TypeInstanceOf extends AbstractRule
{
    protected const NAME = 'instance_of';

    /**
     * @var string
     */
    protected $message = 'The :attribute must be an instance of :type.';

    protected $fillableParams = ['type'];

    /**
     * Modifie le type de l'enumeration
     */
    public function type(string $type): self
    {
        $this->params['type'] = $type;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function check($value): bool
    {
        $this->requireParameters($this->fillableParams);

        if (is_object($type = $this->parameter('type'))) {
            $type = get_class($type);
        }

        if (is_object($value)) {
            return $value instanceof $type || get_class($value) === $type;
        }

        return is_subclass_of($value, $type);
    }
}
