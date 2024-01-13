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

class Titlecase extends AbstractRule
{
    /**
     * Check if the given value must formated in Title case.
     *
     * @see https://en.wikipedia.org/wiki/Title_case
     * @credit <a href="https://github.com/Intervention/validation">Intervention/validation - \Intervention\Validation\Rules\Titlecase</a>
     *
     * @param mixed $value
     */
    public function check($value): bool
    {
        if (! is_string($value)) {
            return false;
        }

        foreach ($this->getWords($value) as $word) {
            if (! $this->isValidWord($word)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get array of words from current value
     */
    private function getWords(string $value): array
    {
        return explode(" ", $value);
    }

    /**
     * Determine if given word starts with upper case letter or number
     */
    private function isValidWord(string $word): bool
    {
        return (bool) preg_match("/^[\p{Lu}0-9]/u", $word);
    }
}
