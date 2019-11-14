<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebsiteController extends Controller
{
  public function index()
	{
		$websites = [
			'farfetch' => 'Farfetch',
			'end' => 'End Clothing',
			'louisvuitton' => 'Louis Vuitton',
			'dior' => 'Dior',
			'offwhite' => 'OFF-WHITE',
		];
		return view('websites.index', compact('websites'));
	}
}
