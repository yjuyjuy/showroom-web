<?php

namespace App\Http\Controllers;

use App\Price;
use Illuminate\Http\Request;

class PricesController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Price  $price
	 * @return \Illuminate\Http\Response
	 */
	public function show(Price $price)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Price  $price
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Price $price)
	{
		$product = $price->product;
		return view('prices.edit', compact('price', 'product'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Price  $price
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Price $price)
	{
		$validated = request()->validate([
			'data' => 'required|array',
			'data.*.size' => ['required','regex:/^([0-9]+)|([X]*[SML]+)$/'],
			'data.*.cost' => ['required','integer'],
			'data.*.resell' => ['required','integer'],
			'data.*.retail' => ['required','integer'],
		]);
		$price->data = $validated['data'];
		return $price->save();
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Price  $price
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Price $price)
	{
		$this->authorize('delete', $price);
		$price->delete();
		return redirect(route(((auth()->user()->isSuperAdmin())?'admin.products.show':'vendors.products.show'), ['product' => $price->product]));
	}
}
