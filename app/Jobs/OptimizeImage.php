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
		// $size_limit1 = 20 * 1024;
		// $size_limit2 = 50 * 1024;

		$path = public_path('storage/'.$this->path);
		$image = \Intervention\Image\Facades\Image::make($path);
		$w = $image->width();
		$h = $image->height();
		// too wide
		if ( $h / $w < 1.35 ) {
			$diff = $this->diff($w, $h, $image);
			if ($diff >= 100) {
				if ($h / $w < 1.0) {
					$diff = $this->diff($w, $h, $image, 1.0);
					if ($diff < 100) {
						$image->crop($h, $h);
					}
				}
				\Intervention\Image\Facades\Image::canvas($w, $w * 1.413, '#ffffff')->insert($image, 'center')->save($path.'_upsized.jpeg', 100, 'jpeg');
				$path = $path.'_upsized.jpeg';
			}
			// too tall
		} elseif ( $h / $w > 1.413) {
			$diff = $this->diff($h, $w, $image);
			if ($avg_diff >= 100) {
				\Intervention\Image\Facades\Image::canvas($h / 1.413, $h, '#ffffff')->insert($image, 'center')->save($path.'_upsized.jpeg', 100, 'jpeg');
				$path = $path.'_upsized.jpeg';
			}
		}
		// $quality = 100;
		// do {
		// $quality = $quality - 10;
		$image = \Intervention\Image\Facades\Image::make($path)->fit(400, 565)->save(public_path('storage/'.$this->path).'_400.jpeg', 50);
		// } while ($image->fileSize() > $size_limit1 && $quality > 30);

		// $quality = 100;
		// $image = \Intervention\Image\Facades\Image::make($path);
		// $width = min(800, $w);
		// $height = min(1130, $h);
		// if ($width * 1.413 > $height) {
		// 	$width = ceil($height / 1.413);
		// } else {
		// 	$height = ceil($width * 1.413);
		// }
		// do {
			// $quality = $quality - 10;
		$image = \Intervention\Image\Facades\Image::make($path)->fit(800, 1130)->save(public_path('storage/'.$this->path).'_800.jpeg', 80);
		// } while ($image->fileSize() > $size_limit2 && $quality > 30);
  }

	public function diff($a, $b, $image, $ratio = 1.413) {
		$a1 = ceil(($a - $b / $ratio) / 2);
		$a2 = ceil($a - $a1);
		$b1 = ceil($b / 3);
		$b2 = ceil($b / 2);
		$b3 = ceil($b / 3 * 2);
		foreach ([$a1, $a2] as $a) {
			foreach ([$b1, $b2, $b3] as $b) {
				$samples[] = array_sum($image->pickColor($a, $b));
			}
		}
		$avg = array_sum($samples) / sizeof($samples);
		$avg_diff = array_sum(array_map(function ($sample) use ($avg) {
			return ($sample - $avg) ** 2;
		}, $samples)) / sizeof($samples);
	}
}
