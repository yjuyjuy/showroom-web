<?php

namespace App\Http\Controllers;

use App\Image;
use App\Product;
use Illuminate\Http\Request;

class ImagesController extends Controller
{

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Product  $product
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Product $product)
	{
		$images = $product->images()->orderBy('website_id', 'asc')->orderBy('type_id', 'asc')->get();
		$images = $images->mapToGroups(function ($item, $key) {
			return [$item->website_id => $item];
		});
		$types = \App\Type::all();
		$websites = \App\Website::all();
		return view('images.edit', compact('product', 'images', 'types', 'websites'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Product  $product
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Product $product)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Product  $product
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Image $image)
	{
		$image->delete();
	}
}
