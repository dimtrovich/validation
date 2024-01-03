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
use ArrayIterator;
use BlitzPHP\Contracts\Support\Arrayable;
use IteratorAggregate;
use Rakit\Validation\ErrorBag as RakitErrorBag;
use Traversable;

class ErrorBag extends RakitErrorBag implements Arrayable, ArrayAccess, IteratorAggregate
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
     * {@inheritDoc}
     */
    public function get(string $key, string $format = ':message'): array
    {
        [$key, $ruleName] = $this->parsekey($key);
        $results          = [];
        if ($this->isWildcardKey($key)) {
            $messages = $this->filterMessagesForWildcardKey($key, $ruleName);

            foreach ($messages as $explicitKey => $keyMessages) {
                foreach ($keyMessages as $rule => $message) {
                    $results[$explicitKey][$rule] = $this->formatMessage($message, $format);
                }
            }
        } else {
            $keyMessages = $this->messages[$key] ?? [];

            foreach ((array) $keyMessages as $rule => $message) {
                if ($ruleName && $ruleName !== $rule) {
                    continue;
                }
                $results[$rule] = $this->formatMessage($message, $format);
            }
        }

        return $results;
    }

    /**
     * {@inheritDoc}
     */
    public function all(string $format = ':message'): array
    {
        $messages = $this->messages;

        $results = [];

        foreach ($messages as $key => $keyMessages) {
            foreach ((array) $keyMessages as $message) {
                $results[] = $this->formatMessage($message, $format);
            }
        }

        return $results;
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
    protected function filterMessagesForWildcardKey(string $key, $ruleName = null): array
    {
        $messages = $this->messages;
        $pattern  = preg_quote($key, '#');
        $pattern  = str_replace('\*', '.*', $pattern);

        $filteredMessages = [];

        foreach ($messages as $k => $keyMessages) {
            if ((bool) preg_match('#^' . $pattern . '\z#u', $k) === false) {
                continue;
            }

            foreach ((array) $keyMessages as $rule => $message) {
                if ($ruleName && $rule !== $ruleName) {
                    continue;
                }
                $filteredMessages[$k][$rule] = $message;
            }
        }

        return $filteredMessages;
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

	/**
     * {@inheritDoc}
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->messages);
    }
}
