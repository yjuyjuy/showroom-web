<?php

namespace App\Observers;

use App\VendorPrice;
use App\OfferPrice;
use App\RetailPrice;
use Illuminate\Support\Arr;

class PriceObserver
{
	/**
	 * Handle the vendor price "created" event.
	 *
	 * @param  \App\VendorPrice  $vendorPrice
	 * @return void
	 */
	public function created(VendorPrice $vendorPrice)
	{
		$product = $vendorPrice->product;
		$vendor = $vendorPrice->vendor;

		$offer = OfferPrice::firstOrNew(['product_id' => $product->id, 'vendor_id' => $vendor->id]);
		$offer->prices = Arr::collapse(array_map(function ($item) {
			return [$item['size'] => $item['offer']];
		}, $vendorPrice->data));
		$offer->save();

		// update retail price for the vendor's own retailer account
		if ($retailer = $vendor->retailer) {
			$retail = RetailPrice::firstOrNew(['retailer_id' => $retailer->id, 'product_id' => $product->id]);
			$prices = $retail->prices ?? array();
			foreach (Arr::collapse(array_map(function ($item) {
				return [$item['size'] => $item['retail']];
			}, $vendorPrice->data)) as $size => $price) {
				if (!array_key_exists($size, $prices) || $price < $prices[$size]) {
					$prices[$size] = $price;
				}
			}
			$retail->prices = $prices;
			$retail->save();
		}

		// update retail price for the vendor's partner_retailers
		foreach ($vendor->partner_retailers as $partner) {
			$retail = RetailPrice::firstOrNew(['retailer_id' => $partner->id, 'product_id' => $product->id]);
			$prices = $retail->prices ?? array();
			foreach ($offer->prices as $size => $price) {
				$price *= $partner->pivot->profit_rate / 100.0 + 1;
				if (!array_key_exists($size, $prices) || $price < $prices[$size]) {
					$prices[$size] = $price;
				}
			}
			$retail->prices = $prices;
			$retail->save();
		}
	}

	/**
	 * Handle the vendor price "updated" event.
	 *
	 * @param  \App\VendorPrice  $vendorPrice
	 * @return void
	 */
	public function updated(VendorPrice $vendorPrice)
	{
		$product = $vendorPrice->product;
		$vendor = $vendorPrice->vendor;

		$offer = $vendorPrice->offer ?? OfferPrice::firstOrNew(['product_id' => $product->id, 'vendor_id' => $vendor->id]);
		$offer->prices = Arr::collapse(array_map(function ($item) {
			return [$item['size'] => $item['offer']];
		}, $vendorPrice->data));
		$offer->save();

		// update retail price for the vendor's own retailer account
		if ($retailer = $vendor->retailer) {
			$retail = $vendorPrice->retail ?? RetailPrice::firstOrNew(['retailer_id' => $retailer->id, 'product_id' => $product->id]);
			$prices = Arr::collapse(array_map(function ($item) {
				return [$item['size'] => $item['retail']];
			}, $vendorPrice->data));
			foreach ($product->offers->whereIn('vendor_id', $retailer->partner_vendors->pluck('id')) as $offer) {
				$profit_rate = $retailer->partner_vendors->firstWhere('id', $offer->vendor_id)->pivot->profit_rate;
				foreach ($offer->prices as $size => $price) {
					$price *= $profit_rate / 100.0 + 1;
					if (!array_key_exists($size, $prices) || $price < $prices[$size]) {
						$prices[$size] = $price;
					}
				}
			}
			if(empty($prices)){
				$retail->delete();
			} else {
				$retail->prices = $prices;
				$retail->save();
			}
		}

		// update retail price for the vendor's partner_retailers
		foreach ($vendor->partner_retailers as $partner_retailer) {
			$retail = RetailPrice::firstOrNew(['retailer_id' => $partner_retailer->id, 'product_id' => $product->id]);
			if($partner_retailer->vendor && $partner_retailer->vendor->prices->where('product_id', $product->id)->first()){
				$prices = Arr::collapse(array_map(function ($item) {
					return [$item['size'] => $item['retail']];
				}, $partner_retailer->vendor->prices->where('product_id', $product->id)->first()->data));
			} else {
				$prices = array();
			}

			foreach ($product->offers->whereIn('vendor_id', $partner_retailer->partner_vendors->pluck('id')) as $offer) {
				$profit_rate = $partner_retailer->partner_vendors->firstWhere('id', $offer->vendor_id)->pivot->profit_rate;
				foreach ($offer->prices as $size => $price) {
					$price *= $profit_rate / 100.0 + 1;
					if (!array_key_exists($size, $prices) || $price < $prices[$size]) {
						$prices[$size] = $price;
					}
				}
			}

			if(empty($prices)){
				$retail->delete();
			} else {
				$retail->prices = $prices;
				$retail->save();
			}

		}
	}

	/**
	 * Handle the vendor price "deleted" event.
	 *
	 * @param  \App\VendorPrice  $vendorPrice
	 * @return void
	 */
	public function deleted(VendorPrice $vendorPrice)
	{
		$vendor = $vendorPrice->vendor;
		$product = $vendorPrice->product;
		$offer = $vendorPrice->offer ?? \App\OfferPrice::where('vendor_id', $vendor->id)->where('product_id', $product->id)->first();
		if ($offer) {
			$offer->delete();
		}

		// update retail price for the vendor's own retailer account
		if ($retailer = $vendor->retailer) {
			$retail = $vendorPrice->retail ?? RetailPrice::where('retailer_id', $retailer->id)->where('product_id', $product->id)->first();
			if ($retail) {
				$prices = array();
				foreach ($product->offers->whereIn('vendor_id', $retailer->partner_vendors->pluck('id')) as $offer) {
					$profit_rate = $retailer->partner_vendors->firstWhere('id', $offer->vendor_id)->pivot->profit_rate;
					foreach ($offer->prices as $size => $price) {
						$price *= $profit_rate / 100.0 + 1;
						if (!array_key_exists($size, $prices) || $price < $prices[$size]) {
							$prices[$size] = $price;
						}
					}
				}
				if(empty($prices)){
					$retail->delete();
				} else {
					$retail->prices = $prices;
					$retail->save();
				}
			}
		}

		// update retail price for the vendor's partner_retailers
		foreach ($vendor->partner_retailers as $partner) {
			$retail = $vendorPrice->retail ?? RetailPrice::where('retailer_id', $partner->id)->where('product_id', $product->id)->first();
			if ($retail) {
				$prices = array();
				foreach ($partner->vendors as $vendor) {
					if ($vendorPrice = $vendor->prices->where('product_id', $product->id)->first()) {
						foreach (Arr::collapse(array_map(function ($item) {
							return [$item['size'] => $item['retail']];
						}, $vendorPrice->data)) as $size => $price) {
							if (!array_key_exists($size, $prices) || $price < $prices[$size]) {
								$prices[$size] = $price;
							}
						}
					}
				}
				foreach ($product->offers->whereIn('vendor_id', $partner->partner_vendors->pluck('id')) as $offer) {
					$profit_rate = $partner->partner_vendors->firstWhere('id', $offer->vendor_id)->pivot->profit_rate;
					foreach ($offer->prices as $size => $price) {
						$price *= $profit_rate / 100.0 + 1;
						if (!array_key_exists($size, $prices) || $price < $prices[$size]) {
							$prices[$size] = $price;
						}
					}
				}
				if(empty($prices)){
					$retail->delete();
				} else {
					$retail->prices = $prices;
					$retail->save();
				}
			}
		}
	}

	/**
	 * Handle the vendor price "restored" event.
	 *
	 * @param  \App\VendorPrice  $vendorPrice
	 * @return void
	 */
	public function restored(VendorPrice $vendorPrice)
	{
		$this->updated($vendorPrice);
	}

	/**
	 * Handle the vendor price "force deleted" event.
	 *
	 * @param  \App\VendorPrice  $vendorPrice
	 * @return void
	 */
	public function forceDeleted(VendorPrice $vendorPrice)
	{
		$this->deleted($vendorPrice);
	}
}
