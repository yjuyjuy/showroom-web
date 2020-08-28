<?php

namespace App\Filters;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class CustomFilter implements FilterInterface
{
    const MAX_RATIO = 2.4; // 21/9 = 2.33333
    const MIN_RATIO = 0.4; // 9/21 = 0.42857
    const MAX_WIDTH = 2560;
    const MIN_WIDTH = 50;
    const MAX_QUALITY = 100;
    const MIN_QUALITY = 10;
    const SUPPORT_FORMATS = ['webp', 'jpg', 'png'];
    const FALLBACK_FORMAT = 'jpg';

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
        $ratio = 1.413;
        $width = 400;
        $format = 'jpg';
        $quality = 90;
        $options = explode('_', $this->options);

        // parse options
        foreach ($options as $option) {
            $matches = array();
            if (preg_match('/w([1-9][0-9]*)/', $option, $matches)) {
                $width = (int)($matches[1]);
            } else if (preg_match('/r([0-9][0-9\.]*)/', $option, $matches)) {
                $ratio = (float)($matches[1]);
            } else if (preg_match('/q([1-9][0-9]*)/', $option, $matches)) {
                $quality = (int)($matches[1]);
            } else if ($option === 'square') {
                $ratio = 1;
            } else if ($option === 'webp') {
                $format = 'webp';
            } else if ($option === 'png') {
                $format = 'png';
            } else if ($option === 'jpeg') {
                $format = 'jpg';
            } else if ($option === 'jpg') {
                $format = 'jpg';
            }
        }

        // process image
        if ($ratio == 1) {
            $side = max($image->width(), $image->height());
            $image->resizeCanvas($side, $side, null, false, 'ffffff');
        } else {
            $ratio = min(max($ratio, self::MIN_RATIO), self::MAX_RATIO);
            if ($image->height() / $image->width() > $ratio) {
                $image->resizeCanvas(round($image->height() / $ratio), $image->height(), null, false, 'ffffff');
            } else {
                $image->resizeCanvas($image->width(), round($image->width() * $ratio), null, false, 'ffffff');
            }
        }
        $width = max(min($width, self::MAX_WIDTH), self::MIN_WIDTH);
        $quality = max(min($quality, self::MAX_QUALITY), self::MIN_QUALITY);
        if (!in_array($format, self::SUPPORT_FORMATS)) {
            $format = self::FALLBACK_FORMAT;
        }
        return $image->widen($width)->encode($format, $quality);
    }
}
