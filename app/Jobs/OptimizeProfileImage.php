<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class OptimizeProfileImage implements ShouldQueue
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
		if ($w != $h) {
			$size = max($w, $h);
			\Intervention\Image\Facades\Image::canvas($size, $size, '#ffffff')->insert($image, 'center')->save($path, 100, 'jpeg');
		}
		\Intervention\Image\Facades\Image::make($path)->fit(400, 400)->save(public_path('storage/'.$this->path).'_400.jpeg', 80);
		\Intervention\Image\Facades\Image::make($path)->fit(800, 800)->save(public_path('storage/'.$this->path).'_800.jpeg', 90);
	}
}
