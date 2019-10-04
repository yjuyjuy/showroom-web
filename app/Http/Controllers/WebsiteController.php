<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebsiteController extends Controller
{
  public function index()
	{
		$websites = [
			'offwhite' => 'OFF-WHITE',
			'farfetch' => 'Farfetch',
			'end' => 'End Clothing',
		];
		return view('websites.index', compact('websites'));
	}
}
