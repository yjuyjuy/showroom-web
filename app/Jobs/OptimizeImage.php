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
			$path = $this->path;
			\Intervention\Image\Facades\Image::make(public_path('storage/'.$path))->fit(400, 565)->save(public_path('storage/'.$path).'_400.jpeg', 80);
			\Intervention\Image\Facades\Image::make(public_path('storage/'.$path))->fit(800, 1130)->save(public_path('storage/'.$path).'_800.jpeg', 80);
    }
}
