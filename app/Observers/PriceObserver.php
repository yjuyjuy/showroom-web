<?php

namespace App\Observers;

use App\VendorPrice;
use App\OfferPrice;
use App\RetailPrice;
use App\Product;
use App\Retailer;
use App\Vendor;
use Illuminate\Support\Arr;

class PriceObserver
{
	public function update_retail(VendorPrice $vendor_price)
	{
		$product = $vendor_price->product;
		$vendor = $vendor_price->vendor;
		$retails = RetailPrice::where('product_id', $product->id)->get();
		$offers = OfferPrice::where('product_id', $product->id)->get();
		$prices = VendorPrice::where('product_id', $product->id)->get();

		$retailers = Retailer::all();
		if($retailer = $vendor->retailer){
			$retailers = $retailers->prepend($retailer)->unique();
		}
		$retailers->load(['vendors', 'partner_vendors', 'vendors.partner_retailers']);

		foreach($retailers as $retailer) {
			$data = array();

			$retail = $retails->firstWhere('retailer_id', $retailer->id);
			if (!$retail) {
				$retail = new RetailPrice();
				$retail->product_id = $product->id;
				$retail->retailer_id = $retailer->id;
			}


			foreach($retailer->vendors as $vendor) {
				if ($vendor_price = $prices->firstWhere('vendor_id', $vendor->id)) {
					foreach($vendor_price->data as $row) {
						$data[$row['size']] = min($row['retail'], $data[$row['size']] ?? INF);
					}
				}
			}

			foreach($retailer->partner_vendors->whereNotIn('id', $retailer->vendors->pluck('id')) as $vendor) {
				if ($offer_price = $offers->firstWhere('vendor_id', $vendor->id)) {
					$profit_rate = $vendor->pivot->profit_rate;
					foreach($offer_price->prices as $size => $price) {
						$calc_price = ceil($price * (1 + $profit_rate / 100.0) / 10.0) * 10 + 60;
						$min_price = 0;
						$data[$size] = min(max($calc_price, $min_price), $data[$size] ?? INF);
					}
				}
			}

			if(empty($data)) {
				$retail->delete();
			} else {
				uksort($data, function($a, $b) {
					$sizes = ['XXS', 'XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'];
					if (in_array($a, $sizes)) {
						$a = array_search($a, $sizes);
					}
					if (in_array($b, $sizes)) {
						$b = array_search($b, $sizes);
					}
					return $a > $b;
				});
				$retail->prices = $data;
				$retail->save();
			}
		}
	}
	public function update_offer(VendorPrice $vendor_price)
	{
		$product = $vendor_price->product;
		$vendor = $vendor_price->vendor;

		$offer = OfferPrice::firstOrNew(['product_id' => $product->id, 'vendor_id' => $vendor->id]);
		$prices = array();
		foreach($vendor_price->data as $data) {
			$prices[$data['size']] = $data['offer'];
		}

		uksort($prices, function($a, $b) {
			$sizes = ['XXS', 'XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'];
			if (in_array($a, $sizes)) {
				$a = array_search($a, $sizes);
			}
			if (in_array($b, $sizes)) {
				$b = array_search($b, $sizes);
			}
			return $a > $b;
		});

		$offer->prices = $prices;
		$offer->save();
	}
	public function delete_offer(VendorPrice $vendor_price)
	{
		$product = $vendor_price->product;
		$vendor = $vendor_price->vendor;

		$offer = OfferPrice::firstOrNew(['product_id' => $product->id, 'vendor_id' => $vendor->id]);
		$offer->delete();
	}
	public function created(VendorPrice $vendor_price)
	{
		$this->update_offer($vendor_price);
		$this->update_retail($vendor_price);
	}
	public function updated(VendorPrice $vendor_price)
	{
		$this->update_offer($vendor_price);
		$this->update_retail($vendor_price);
	}
	public function deleted(VendorPrice $vendor_price)
	{
		$this->delete_offer($vendor_price);
		$this->update_retail($vendor_price);
	}
}
