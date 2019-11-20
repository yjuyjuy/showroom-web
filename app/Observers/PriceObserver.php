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

		$retailers = $vendor->partner_retailers;
		if($retailer = $vendor->retailer){
			$retailers->push($retailer);
		}
		foreach($retailers as $retailer) {
			$prices = array();
			$retail = RetailPrice::firstOrNew(['product_id' => $product->id, 'retailer_id' => $retailer->id]);
			foreach($retailer->vendors as $vendor) {
				if ($vendor_price = VendorPrice::where(['product_id' => $product->id, 'vendor_id' => $vendor->id])->first()) {
					foreach($vendor_price->data as $data) {
						$prices[$data['size']] = min($data['retail'], $prices[$data['size']] ?? INF);
					}
				}
			}

			foreach($retailer->partner_vendors->whereNotIn('id', $retailer->vendors->pluck('id')) as $vendor) {
				if ($offer_price = OfferPrice::where(['product_id' => $product->id, 'vendor_id' => $vendor->id])->first()) {
					$profit_rate = $vendor->pivot->profit_rate;
					foreach($offer_price->prices as $size => $price) {
						$calc_price = ceil($price * (1 + $profit_rate / 100.0) / 10.0) * 10;
						if ($vendor->retailer) {
							$min_price = \App\RetailPrice::firstWhere(['product_id' => $product->id, 'retailer_id' => $vendor->retailer_id,])->prices[$size] + 1;
						} else {
							$min_price = 0;
						}
						$prices[$size] = min(max($calc_price, $min_price), $prices[$size] ?? INF);
					}
				}
			}

			if(empty($prices)) {
				$retail->delete();
			} else {
				$retail->prices = $prices;
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
