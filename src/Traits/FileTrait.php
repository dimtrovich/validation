<?php

/**
 * This file is part of Dimtrovich/Validation.
 *
 * (c) 2023 Dimitri Sitchet Tomkeu <devcode.dst@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Dimtrovich\Validation\Traits;

use BlitzPHP\Filesystem\Files\File;
use BlitzPHP\Filesystem\Files\UploadedFile;
use Rakit\Validation\Rules\Traits\FileTrait as TraitsFileTrait;

trait FileTrait
{
    use TraitsFileTrait;

    /**
     * Check that the given value is a valid file instance.
     */
    public function isValidFileInstance(mixed $value): bool
    {
        if (class_exists(UploadedFile::class) && $value instanceof UploadedFile) {
            return $value->isValid();
        }

        return class_exists(File::class) && $value instanceof File;
    }

    /**
     * Check if PHP uploads are explicitly allowed.
     *
     * @param array<int, int|string> $parameters
     */
    protected function shouldBlockPhpUpload(mixed $value, array $parameters): bool
    {
        if (in_array('php', $parameters, true)) {
            return false;
        }

        $phpExtensions = [
            'php', 'php3', 'php4', 'php5', 'php7', 'php8', 'phtml', 'phar',
        ];

        return in_array(trim(strtolower($value->clientExtension())), $phpExtensions, true);
    }
}
