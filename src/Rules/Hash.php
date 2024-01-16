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

use InvalidArgumentException;

class Hash extends AbstractRule
{
    /**
     * @var array
     */
    protected $fillableParams = ['algo', 'allowUppercase'];

    private array $algorithmsLengths = [
        'MD5'    => 32,
        'SHA1'   => 40,
        'SHA256' => 64,
        'SHA512' => 128,
        'CRC32'  => 8,
    ];

    /**
     * Check if the given value is a valid cryptographic hash.
     *
     * @credit <a href="https://github.com/particle-php/Validator/">particle-php/Validator - Particle\Validator\Rule\Hash</a>
     *
     * @param mixed $value
     */
    public function check($value): bool
    {
        $this->requireParameters(['algo']);

        $algo = strtoupper($this->parameter('algo'));
        
        if (! isset($this->algorithmsLengths[$algo])) {
            $algos = array_keys($this->algorithmsLengths);

           throw new InvalidArgumentException('algo parameter must be one of ' . implode('/', $algos));
        }

        $this->setParameterText('algorithm', $algo);

        $length        = $this->algorithmsLengths[$algo];
        $caseSensitive = boolval($this->parameter('allowUppercase', false)) ? 'i' : '';

        return preg_match(sprintf('/^[0-9a-f]{%s}$/%s', $length, $caseSensitive), $value) === 1;
    }
}
