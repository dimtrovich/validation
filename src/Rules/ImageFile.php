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

class ImageFile extends File
{
    /**
     * Create a new image file rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->rules('image');
    }

    /**
     * The dimension constraints for the uploaded file.
     */
    public function dimensions(Dimensions $dimensions)
    {
        $this->rules([$dimensions]);

        return $this;
    }
}
