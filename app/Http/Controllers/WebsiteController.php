<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebsiteController extends Controller
{
	public function index()
	{
		$websites = [
			'louisvuitton' => 'Louis Vuitton',
			'farfetch' => 'Farfetch',
			'end' => 'End Clothing',
			'dior' => 'Dior',
			'gucci' => 'Gucci',
			'offwhite' => 'OFF-WHITE',
			'balenciaga' => 'Balenciaga',
			'ssense' => 'SSENSE',
		];
		return view('websites.index', compact('websites'));
	}
}
