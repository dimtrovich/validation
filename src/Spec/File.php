<?php

/**
 * This file is part of Dimtrovich/Validation.
 *
 * (c) 2023 Dimitri Sitchet Tomkeu <devcode.dst@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Dimtrovich\Validation\Spec;

use BlitzPHP\Filesystem\Files\Mimes;
use BlitzPHP\Filesystem\Files\UploadedFile;
use Rakit\Validation\MimeTypeGuesser;

class File extends UploadedFile
{
    /**
     * The name of the file.
     *
     * @var string
     */
    public $name;

    /**
     * The temporary file resource.
     *
     * @var resource
     */
    public $tempFile;

    /**
     * The "size" to report.
     *
     * @var int
     */
    public $sizeToReport;

    /**
     * The MIME type to report.
     *
     * @var string|null
     */
    public $mimeTypeToReport;

    /**
     * Create a new file instance.
     *
     * @param  resource  $tempFile
     * @return void
     */
    public function __construct(string $name, $tempFile)
    {
        $this->name = $name;
        $this->tempFile = $tempFile;

        parent::__construct($this->tempFilePath(), null, 0, $name, $this->getMimeType());
    }

    /**
     * Create a new fake file.
     */
    public static function create(string $name, int|string $kilobytes = 0): static
    {
        return (new FileFactory)->create($name, $kilobytes);
    }

    /**
     * Create a new fake file with content.
     */
    public static function createWithContent(string $name, string $content): static
    {
        return (new FileFactory)->createWithContent($name, $content);
    }

    /**
     * Create a new fake image.
     */
    public static function image(string $name, int $width = 10, int $height = 10): static
    {
        return (new FileFactory)->image($name, $width, $height);
    }

    /**
     * Set the "size" of the file in kilobytes.
     */
    public function size(int $kilobytes): static
    {
        $this->sizeToReport = $kilobytes * 1024;

        return $this;
    }

    /**
     * Get the size of the file.
     */
    public function getSize(): int
    {
        return $this->sizeToReport ?: parent::getSize();
    }

    /**
     * Set the "MIME type" for the file.
     */
    public function mimeType(string $mimeType): static
    {
        $this->mimeTypeToReport = $mimeType;

        return $this;
    }

    /**
     * Get the MIME type of the file.
     */
    public function getMimeType(): string
    {
        if (null === $this->mimeTypeToReport) {
            $extension = pathinfo($this->name, PATHINFO_EXTENSION);

            $this->mimeTypeToReport = Mimes::guessTypeFromExtension($extension);
        }

        return $this->mimeTypeToReport ?? '';
    }

    /**
     * Get the path to the temporary file.
     */
    protected function tempFilePath(): string
    {
        return stream_get_meta_data($this->tempFile)['uri'];
    }
}
