<?php

namespace App\Http\Controllers\api\v3\customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Retailer;
use App\RetailPrice;
use Illuminate\Support\Facades\Cache;

class RetailController extends Controller
{
	public function index(Request $request)
	{
		// return Cache::remember($request->fullUrl(), 1 * 60, function() use ($request) {
		$ITEMS_PER_PAGE = 24;
		if ($request->query('retailer') && $retailer = Retailer::where('name', $request->query('retailer'))->first()) {
			$query = $retailer->retails();
		} else {
			$query = RetailPrice::whereIn('retailer_id', auth()->user()->following_retailers()->pluck('retailer_id'));
		}
		$filters = $this->validateFilters();
		foreach ($filters as $field => $values) {
			$query->whereHas('product', function ($query) use ($field, $values) {
				$query->whereIn("{$field}_id", $values);
			});
		}
		$query->orderBy('updated_at', 'desc');
		$total_pages = ceil($query->count() / $ITEMS_PER_PAGE);
		$page = min(max(request()->query('page', 1), 1), $total_pages);
		$retails = $query->forPage($page, $ITEMS_PER_PAGE)->get();
		$retails->loadMissing(['retailer', 'retailer.image', 'product', 'product.brand', 'product.season', 'product.images',
			'product.retails' => function($query) use ($user) {
				$query->whereIn('retailer_id', $user->following_retailer->pluck('id'));
			}, 'product.retails.retailer']);
		return [
			'page' => $page,
			'total_pages' => $total_pages,
			'prices' => $retails->values(),
			'filter_options' => $this->filterOptions(),
		];
		// });
	}

	public function validateFilters()
	{
		return (new \App\Http\Controllers\ProductController())->validateFilters();
	}

	public function filterOptions($value = '')
	{
		return (new \App\Http\Controllers\ProductController())->filterOptions();
	}
}
