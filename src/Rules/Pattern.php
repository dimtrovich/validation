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

class Pattern extends AbstractRule
{
    /**
     * @var array
     */
    protected $fillableParams = ['length', 'separator'];

    /**
     * Check texts with specific pattern.
     *
     * @credit <a href="https://github.com/milwad-dev/laravel-validate">milwad/laravel-validate - Milwad\LaravelValidate\Rules\ValidPattern</a>
     *
     * @param mixed $value
     */
    public function check($value): bool
    {
        if (! is_string($value)) {
            return false;
        }

        $this->requireParameters(['length']);

        $length    = (int) $this->parameter('length');
        $separator = $this->parameter('separator');

        $texts = explode($separator ?: '-', $value);

        foreach ($texts as $text) {
            if (strlen($text) !== $length) {
                return false;
            }
        }

        return true;
    }
}
