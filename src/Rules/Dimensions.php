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

use BlitzPHP\Filesystem\Files\UploadedFile;
use Dimtrovich\Validation\Traits\FileTrait;

class Dimensions extends AbstractRule
{
    use FileTrait;

    /**
     * @var array
     */
    protected $fillableParams = ['dimensions'];

    /**
     * {@inheritDoc}
     */
    public function check($value): bool
    {
        if ($this->isValidFileInstance($value)
            && in_array($value->getMimeType(), ['image/svg+xml', 'image/svg'], true)) {
            return true;
        }

        $this->requireParameters($this->fillableParams);

        if ($this->isValueFromUploadedFiles($value)) {
            $value = new UploadedFile(
                $value['tmp_name'],
                (int) $value['size'],
                $value['error'],
                $value['name'] ?? null,
                $value['type'] ?? null
            );
        }

        if (! $this->isValidFileInstance($value)) {
            return false;
        }

        $dimensions = method_exists($value, 'dimensions')
            ? $value->dimensions()
            : @getimagesize($value->realPath());

        if (! $dimensions) {
            return false;
        }

        [$width, $height] = $dimensions;

        $parameters = $this->parseNamedParameters($this->params);

        return ! ($this->failsBasicDimensionChecks($parameters, $width, $height)
            || $this->failsRatioCheck($parameters, $width, $height));
    }

    /**
     * Test if the given width and height fail any conditions.
     *
     * @param array<string,string> $parameters
     */
    protected function failsBasicDimensionChecks(array $parameters, int $width, int $height): bool
    {
        $parameters = array_map(fn($v) => (int) $v, $parameters);
        
        return (isset($parameters['width']) && $parameters['width'] !== $width)
               || (isset($parameters['min_width']) && $parameters['min_width'] > $width)
               || (isset($parameters['max_width']) && $parameters['max_width'] < $width)
               || (isset($parameters['height']) && $parameters['height'] !== $height)
               || (isset($parameters['min_height']) && $parameters['min_height'] > $height)
               || (isset($parameters['max_height']) && $parameters['max_height'] < $height);
    }

    /**
     * Determine if the given parameters fail a dimension ratio check.
     *
     * @param array<string,string> $parameters
     */
    protected function failsRatioCheck(array $parameters, int $width, int $height): bool
    {
        if (! isset($parameters['ratio'])) {
            return false;
        }

        [$numerator, $denominator] = array_replace(
            [1, 1],
            array_filter(sscanf($parameters['ratio'], '%f/%d'))
        );

        $precision = 1 / (max($width, $height) + 1);

        return abs($numerator / $denominator - $width / $height) > $precision;
    }
}
