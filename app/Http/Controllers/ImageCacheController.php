<?php

namespace App\Http\Controllers;

use Intervention\Image\ImageCacheController as BaseController;
use Intervention\Image\ImageManager;

use App\Filters\CustomFilter;

class ImageCacheController extends BaseController
{
    public function getImage($options, $filename)
    {
        $path = $this->getImagePath($filename);

        // image manipulation based on callback
        $manager = new ImageManager(config('image'));
        $content = $manager->cache(function ($image) use ($options, $path) {
            $image->make($path)->filter(new CustomFilter($options));
        }, config('imagecache.lifetime'));

        return $this->buildResponse($content);
    }
}
