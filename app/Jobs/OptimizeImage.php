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
			$size_limit1 = 15 * 1024;
			$size_limit2 = 50 * 1024;

			$path = public_path('storage/'.$this->path);
			$image = \Intervention\Image\Facades\Image::make($path);
			$w = $image->width();
			$h = $image->height();
			if ( $h / $w < 1.35 ) {
				$x1 = ceil(($w - $h / 1.413) / 2);
				$x2 = ceil($w - $x1);
				$y1 = ceil($h / 3);
				$y2 = ceil($h / 2);
				$y3 = ceil($h / 3 * 2);
				foreach ([$x1, $x2] as $x) {
					foreach ([$y1, $y2, $y3] as $y) {
						$samples[] = array_sum($image->pickColor($x, $y));
					}
				}
				$avg = array_sum($samples) / sizeof($samples);
				$avg_diff = array_sum(array_map(function ($sample) use ($avg) {
					return ($sample - $avg) ** 2;
				}, $samples)) / sizeof($samples);
				\Intervention\Image\Facades\Image::canvas($w, $w * 1.413, '#ffffff')->insert($image, 'center')->save($path.'_upsized.jpeg', 100, 'jpeg');
				if ($avg_diff >= 100) {
					$path = $path.'_upsized.jpeg';
				}
			}
			$quality = 100;
			do {
				$quality = $quality - 10;
				$image = \Intervention\Image\Facades\Image::make($path)->fit(400, 565)->save(public_path('storage/'.$this->path).'_400.jpeg', $quality);
			} while ($image->fileSize() > $size_limit1 && $quality > 20);

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
			} while ($image->fileSize() > $size_limit2 && $quality > 20);
    }
}
