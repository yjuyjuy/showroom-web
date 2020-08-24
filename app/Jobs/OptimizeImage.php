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
	
	const DEFAULT_RATIO = 1.413;
	
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

		$path = public_path('storage/' . $this->path);
		$image = \Intervention\Image\Facades\Image::make($path);
		$w = $image->width();
		$h = $image->height();
		// too wide
		if ($h / $w < 1.35) {
			$diff = $this->diff($w, $h, $image, self::DEFAULT_RATIO);
			if ($diff >= 100) {
				$isCropped = false;
				// width > height
				if ($h / $w < 1.0) {
					$diff = $this->diff($w, $h, $image, 1.0);
					// crop to square then upsize
					if ($diff < 100) {
						$image->crop($h, $h)->resizeCanvas($h, $h * self::DEFAULT_RATIO, null, false, '#ffffff')->save($path . '_upsized.jpeg', 100, 'jpeg');
						$path = $path . '_upsized.jpeg';
						$isCropped = true;
					}
				}
				// upsize
				if (!$isCropped) {
					$image->resizeCanvas($w, $w * self::DEFAULT_RATIO, null, false, '#ffffff')->save($path . '_upsized.jpeg', 100, 'jpeg');
					$path = $path . '_upsized.jpeg';
				}
			} else {
				$image->resizeCanvas($w, $w * self::DEFAULT_RATIO, null, false, '#ffffff')->save($path . '_upsized.jpeg', 100, 'jpeg');
			}
			// too tall
		} elseif ($h / $w > self::DEFAULT_RATIO) {
			$diff = $this->diff($w, $h, $image, self::DEFAULT_RATIO);
			if ($diff >= 100) {
				$image->resizeCanvas($h / self::DEFAULT_RATIO, $h, null, false, '#ffffff')->save($path . '_upsized.jpeg', 100, 'jpeg');
				$path = $path . '_upsized.jpeg';
			}
		}
		// $quality = 100;
		// do {
		// $quality = $quality - 10;
		$image = \Intervention\Image\Facades\Image::make($path)->fit(400, 565)->save(public_path('storage/' . $this->path) . '_400.jpeg', 50);
		// } while ($image->fileSize() > $size_limit1 && $quality > 30);

		// $quality = 100;
		// $image = \Intervention\Image\Facades\Image::make($path);
		// $width = min(800, $w);
		// $height = min(1130, $h);
		// if ($width * self::DEFAULT_RATIO > $height) {
		// 	$width = ceil($height / self::DEFAULT_RATIO);
		// } else {
		// 	$height = ceil($width * self::DEFAULT_RATIO);
		// }
		// do {
		// $quality = $quality - 10;
		$image = \Intervention\Image\Facades\Image::make($path)->fit(800, 1130)->save(public_path('storage/' . $this->path) . '_800.jpeg', 80);
		// } while ($image->fileSize() > $size_limit2 && $quality > 30);
	}

	public function diff($w, $h, $image, $ratio)
	{
		if ($h / $w < $ratio) {
			$x1 = ceil(($w - $h / $ratio) / 2);
			$x2 = ceil($w - $x1);
			$y1 = ceil($h * 1 / 5);
			$y2 = ceil($h * 2 / 5);
			$y3 = ceil($h * 3 / 5);
			$y4 = ceil($h * 4 / 5);
			$samples = [];
			foreach ([$x1, $x2] as $x) {
				foreach ([$y1, $y2, $y3, $y4] as $y) {
					$samples[] = array_sum($image->pickColor($x, $y));
				}
			}
		} else {
			$y1 = ceil(($h - $w * $ratio) / 2);
			$y2 = ceil($h - $y1);
			$x1 = ceil($w / 3);
			$x2 = ceil($w / 2);
			$x3 = ceil($w / 3 * 2);
			foreach ([$y1, $y2] as $y) {
				foreach ([$x1, $x2, $x3] as $x) {
					$samples[] = array_sum($image->pickColor($x, $y));
				}
			}
		}
		$avg = array_sum($samples) / sizeof($samples);
		return array_sum(array_map(function ($sample) use ($avg) {
			return ($sample - $avg) ** 2;
		}, $samples)) / sizeof($samples);
	}
}
