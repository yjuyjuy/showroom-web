<?php

namespace App\Filters;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class CustomFilter implements FilterInterface
{
    const DEFAULT_RATIO = 1.413;

    private string $options;

    /**
     * Creates new instance of filter
     *
     * @param integer $size
     */
    public function __construct($options)
    {
        $this->options = $options;
    }

    /**
     * Applies filter effects to given image
     *
     * @param  Intervention\Image\Image $image
     * @return Intervention\Image\Image
     */
    public function applyFilter(Image $image)
    {
        $options = explode('_', $this->options);
        if (in_array('square', $options)) {
            $side = max($image->width(), $image->height());
            $image->resizeCanvas($side, $side, null, false, 'ffffff');
        } else {
            $ratio = self::DEFAULT_RATIO;
            if ($image->height() / $image->width() > $ratio) {
                $image->resizeCanvas(round($image->height() / $ratio), $image->height(), null, false, 'ffffff');
            } else {
                $image->resizeCanvas($image->width(), round($image->width() * $ratio), null, false, 'ffffff');
            }
        }
        foreach ($options as $option) {
            $matches = array();
            if (preg_match('/w([1-9][0-9]*)/', $option, $matches)) {
                $image->widen((int)($matches[1]));
            }
        }
        if (in_array('webp', $options)) {
            $image->encode('webp');
        } else if (in_array('jpg', $options)) {
            $image->encode('jpg');
        } else {
            $image->encode('jpg');
        }
        return $image;
    }
}
