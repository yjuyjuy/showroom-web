<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class OptimizeImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

		protected $path;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($path)
    {
      $this->path = $path;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
			$path = public_path('storage/'.$this->path);
			$image = \Intervention\Image\Facades\Image::make($path);
			$w = $image->width();
			$h = $image->height();
			if ( $h / $w < 1.2 ) {
				$x1 = ceil(($w - $h / 1.413) / 2);
				$x2 = ceil($w - $x1);
				$y1 = ceil($h / 3);
				$y2 = ceil($h / 2);
				$y2 = ceil($h / 3 * 2);
				$color1 = $image->pickColor($x1, $y1);
				$color2 = $image->pickColor($x1, $y2);
				$color3 = $image->pickColor($x2, $y1);
				$color4 = $image->pickColor($x2, $y2);
				$avg_diff = (array_sum([255,255,255]) * 4 - (array_sum($color1) + array_sum($color2) + array_sum($color3) + array_sum($color4))) / 4;
				if ($avg_diff >= 100) {
					$path = $path.'_upsized.jpeg';
					\Intervention\Image\Facades\Image::canvas($w, $w * 1.413, '#ffffff')->insert($image, 'center')->save($path, 100, 'jpeg');
				}
			}
			$quality = 100;
			do {
				$quality = $quality - 10;
				$image = \Intervention\Image\Facades\Image::make($path)->fit(400, 565)->save(public_path('storage/'.$this->path).'_400.jpeg', $quality);
			} while ($image->fileSize() > 15 * 1024 && $quality > 20);

			$quality = 100;
			$image = \Intervention\Image\Facades\Image::make($path);
			$width = min(800, $w);
			$height = min(1130, $h);
			if ($width * 1.413 > $height) {
				$width = ceil($height / 1.413);
			} else {
				$height = ceil($width * 1.413);
			}
			do {
				$quality = $quality - 10;
				$image = \Intervention\Image\Facades\Image::make($path)->fit($width, $height)->save(public_path('storage/'.$this->path).'_800.jpeg', $quality);
			} while ($image->fileSize() > 30 * 1024 && $quality > 20);
    }
}
