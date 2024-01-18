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

use BlitzPHP\Traits\Conditionable;
use BlitzPHP\Traits\Macroable;
use BlitzPHP\Utilities\Helpers;
use BlitzPHP\Utilities\Iterable\Arr;
use BlitzPHP\Utilities\String\Text;
use BlitzPHP\Utilities\Support\Invader;
use Dimtrovich\Validation\Traits\FileTrait;
use Dimtrovich\Validation\Validator;
use InvalidArgumentException;

class File extends AbstractRule
{
    use Macroable;
    use FileTrait;
    use Conditionable;

    /**
     * The MIME types that the given file should match. This array may also contain file extensions.
     */
    protected array $allowedMimetypes = [];

    /**
     * The extensions that the given file should match.
     */
    protected array $allowedExtensions = [];

    /**
     * The minimum size in kilobytes that the file can be.
     */
    protected ?int $minimumFileSize = null;

    /**
     * The maximum size in kilobytes that the file can be.
     */
    protected ?int $maximumFileSize = null;

    /**
     * An array of custom rules that will be merged into the validation rules.
     */
    protected array $customRules = [];

    /**
     * The callback that will generate the "default" version of the file rule.
     *
     * @var string|array|callable|null
     */
    public static $defaultCallback;

    /**
     * Set the default callback to be used for determining the file default rules.
     *
     * If no arguments are passed, the default file rule configuration will be returned.
     *
     * @param  static|callable|null  $callback
     * @return static|null
     */
    public static function defaults($callback = null)
    {
        if (is_null($callback)) {
            return static::default();
        }

        if (! is_callable($callback) && ! $callback instanceof static) {
            throw new InvalidArgumentException('The given callback should be callable or an instance of '.static::class);
        }

        static::$defaultCallback = $callback;
    }

    /**
     * Get the default configuration of the file rule.
     */
    public static function default(): static
    {
        $file = is_callable(static::$defaultCallback)
            ? call_user_func(static::$defaultCallback)
            : static::$defaultCallback;

        return $file instanceof self ? $file : new self();
    }

    /**
     * Limit the uploaded file to only image types.
     */
    public static function image(): ImageFile
    {
        return new ImageFile();
    }

    /**
     * Limit the uploaded file to the given MIME types or file extensions.
     *
     * @param  string|array<int, string>  $mimetypes
     */
    public static function types(array|string $mimetypes): static
    {
        return Helpers::tap(new static(), fn ($file) => $file->allowedMimetypes = (array) $mimetypes);
    }

    /**
     * Limit the uploaded file to the given file extensions.
     *
     * @param  string|array<int, string>  $extensions
     */
    public function extensions(array|string $extensions): static
    {
        $this->allowedExtensions = (array) $extensions;

        return $this;
    }

    /**
     * Indicate that the uploaded file should be exactly a certain size in kilobytes.
     */
    public function size(int|string $size): static
    {
        $this->minimumFileSize = $this->toKilobytes($size);
        $this->maximumFileSize = $this->minimumFileSize;

        return $this;
    }

    /**
     * Indicate that the uploaded file should be between a minimum and maximum size in kilobytes.
     */
    public function between(int|string $minSize, int|string $maxSize): static
    {
        $this->minimumFileSize = $this->toKilobytes($minSize);
        $this->maximumFileSize = $this->toKilobytes($maxSize);

        return $this;
    }

    /**
     * Indicate that the uploaded file should be no less than the given number of kilobytes.
     */
    public function min(int|string $size): static
    {
        $this->minimumFileSize = $this->toKilobytes($size);

        return $this;
    }

    /**
     * Indicate that the uploaded file should be no more than the given number of kilobytes.
     */
    public function max(int|string $size)
    {
        $this->maximumFileSize = $this->toKilobytes($size);

        return $this;
    }

    /**
     * Convert a potentially human-friendly file size to kilobytes.
     *
     * @return float|int
     */
    protected function toKilobytes(int|string $size)
    {
        if (! is_string($size)) {
            return $size;
        }

        $value = floatval($size);

        return round(match (true) {
            Text::endsWith($size, 'kb') => $value * 1,
            Text::endsWith($size, 'mb') => $value * 1000,
            Text::endsWith($size, 'gb') => $value * 1000000,
            Text::endsWith($size, 'tb') => $value * 1000000000,
            default => throw new InvalidArgumentException('Invalid file size suffix.'),
        });
    }

    /**
     * Specify additional validation rules that should be merged with the default rules during validation.
     */
    public function rules(array|string $rules): static
    {
        $this->customRules = array_merge($this->customRules, Arr::wrap($rules));

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function check($value): bool
    {
        $attribute = $this->getAttribute()->getKey();

        $validator = Validator::make(
            [$attribute => $value],
            [$attribute => $this->buildValidationRules()],
            $this->validation->getMessages(),
            Invader::make($this->validation)->aliases
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->toArray() as $error) {
                foreach ($error as $rule => $message) {
                    $this->validation->errors()->add($attribute, $rule, $message);
                }
            }

            return false;
        }

        return true;
    }

    /**
     * Build the array of underlying validation rules based on the current state.
     */
    protected function buildValidationRules(): array
    {
        $_this = $this;

        $rules = [
            fn ($value) => $_this->isValidFileInstance($value),
        ];

        $rules = array_merge($rules, $this->buildMimetypes());

        if (! empty($this->allowedExtensions)) {
            $rules[] = 'ext:'.implode(',', array_map('strtolower', $this->allowedExtensions));
        }

        $rules[] = match (true) {
            is_null($this->minimumFileSize) && is_null($this->maximumFileSize) => null,
            is_null($this->maximumFileSize) => "min:{$this->minimumFileSize}",
            is_null($this->minimumFileSize) => "max:{$this->maximumFileSize}",
            $this->minimumFileSize !== $this->maximumFileSize => "between:{$this->minimumFileSize},{$this->maximumFileSize}",
            default => "size:{$this->minimumFileSize}",
        };

        return array_merge(array_filter($rules), $this->customRules);
    }

    /**
     * Separate the given mimetypes from extensions and return an array of correct rules to validate against.
     */
    protected function buildMimetypes(): array
    {
        if (count($this->allowedMimetypes) === 0) {
            return [];
        }

        $rules = [];

        $mimetypes = array_filter(
            $this->allowedMimetypes,
            fn ($type) => str_contains($type, '/')
        );

        $mimes = array_diff($this->allowedMimetypes, $mimetypes);

        if (count($mimetypes) > 0) {
            $rules[] = 'mimetypes:'.implode(',', $mimetypes);
        }

        if (count($mimes) > 0) {
            $rules[] = 'mimes:'.implode(',', $mimes);
        }

        return $rules;
    }
}
