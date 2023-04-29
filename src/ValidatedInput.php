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

use ArrayIterator;
use BlitzPHP\Utilities\Iterable\Arr;
use BlitzPHP\Utilities\Iterable\Collection;
use Dimtrovich\Validation\Contracts\ValidatedData;
use stdClass;
use Traversable;

class ValidatedInput implements ValidatedData
{
    /**
     * Create a new validated input container.
     *
     * @param array $input The underlying input.
     */
    public function __construct(protected array $input)
    {
    }

    /**
     * Determine if the validated input has one or more keys.
     */
    public function has(mixed $keys): bool
    {
        $keys = is_array($keys) ? $keys : func_get_args();

        foreach ($keys as $key) {
            if (! Arr::has($this->input, $key)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Determine if the validated input is missing one or more keys.
     */
    public function missing(mixed $keys): bool
    {
        $keys = is_array($keys) ? $keys : func_get_args();

        return ! $this->has($keys);
    }

    /**
     * Get a subset containing the provided keys with values from the input data.
     */
    public function only(mixed $keys): array
    {
        $results = [];

        $input = $this->input;

        $placeholder = new stdClass();

        foreach (is_array($keys) ? $keys : func_get_args() as $key) {
            $value = Arr::dataGet($input, $key, $placeholder);

            if ($value !== $placeholder) {
                Arr::set($results, $key, $value);
            }
        }

        return $results;
    }

    /**
     * Get all of the input except for a specified array of items.
     */
    public function except(mixed $keys): array
    {
        $keys = is_array($keys) ? $keys : func_get_args();

        $results = $this->input;

        Arr::forget($results, $keys);

        return $results;
    }

    /**
     * Merge the validated input with the given array of additional data.
     */
    public function merge(array $items)
    {
        return new self(array_merge($this->input, $items));
    }

    /**
     * Get the input as a collection.
     */
    public function collect(): Collection
    {
        return new Collection($this->input);
    }

    /**
     * Get the raw, underlying input array.
     */
    public function all(): array
    {
        return $this->input;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return $this->all();
    }

    /**
     * Recuperation dynamique des donnees.
     */
    public function __get(string $name): mixed
    {
        return $this->input[$name] ?? null;
    }

    /**
     * Definition dynamique des donnnees
     *
     * @return mixed
     */
    public function __set(string $name, mixed $value)
    {
        $this->input[$name] = $value;
    }

    /**
     * Determine si une cle existe parmis les donnees.
     */
    public function __isset(string $name): bool
    {
        return isset($this->input[$name]);
    }

    /**
     * Retire une donnees
     */
    public function __unset(string $name)
    {
        unset($this->input[$name]);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetExists($key): bool
    {
        return isset($this->input[$key]);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetGet($key): mixed
    {
        return $this->input[$key];
    }

    /**
     * {@inheritDoc}
     */
    public function offsetSet($key, $value): void
    {
        if (null === $key) {
            $this->input[] = $value;
        } else {
            $this->input[$key] = $value;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function offsetUnset($key): void
    {
        unset($this->input[$key]);
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->input);
    }
}
