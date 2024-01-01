<?php

/**
 * This file is part of Dimtrovich/Validation.
 *
 * (c) 2023 Dimitri Sitchet Tomkeu <devcode.dst@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Dimtrovich\Validation;

use ArrayAccess;
use BlitzPHP\Contracts\Support\Arrayable;
use Rakit\Validation\ErrorBag as RakitErrorBag;
use Traversable;

class ErrorBag extends RakitErrorBag implements Arrayable, ArrayAccess, Traversable
{
    /**
     * Returns key validation errors as a string
     */
    public function line(string $key, string $separator = ', ', string $format = ':message'): ?string
    {
        if ([] === $errors = $this->get($key, $format)) {
            return null;
        }

        return implode($separator, $errors);
    }

    /**
     * Creates a new error bag from base errors
     */
    public static function fromBase(RakitErrorBag $bag): static
    {
        return new static($bag->toArray());
    }

    /**
     * {@inheritDoc}
     */
    public function offsetExists(mixed $offset): bool
    {
        return $this->has((string) $offset);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->get((string) $offset);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->messages[$offset] = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function offsetUnset(mixed $offset): void
    {
        unset($this->messages[$offset]);
    }
}
