<?php

namespace App\Observers;

use App\VendorPrice;
use App\OfferPrice;
use App\RetailPrice;
use App\Product;
use App\Retailer;
use Illuminate\Support\Arr;

class PriceObserver
{
	public function handle(VendorPrice $vendor_price)
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

		$retailers = $vendor->partner_retailers;
		if($retailer = $vendor->retailer){
			$retailers->push($retailer);
		}
		foreach($retailers as $retailer) {
			$this->update_retailer($product, $retailer);
		}
	}

	public function update_retailer(Product $product, Retailer $retailer)
	{
		$prices = array();
		$retail = RetailPrice::firstOrNew(['product_id' => $product->id, 'retailer_id' => $retailer->id]);
		foreach($retailer->vendors as $vendor) {
			if ($vendor_price = VendorPrice::where(['product_id' => $product->id, 'vendor_id' => $vendor->id])->first()) {
				foreach($vendor_price->data as $data) {
					$prices[$data['size']] = min($data['retail'], $prices[$data['size']] ?? INF);
				}
			}
		}

		foreach($retailer->partner_vendors as $vendor) {
			if ($offer_price = OfferPrice::where(['product_id' => $product->id, 'vendor_id' => $vendor->id])->first()) {
				$profit_rate = $vendor->pivot->profit_rate;
				foreach($offer_price->prices as $size => $price) {
					$calc_price = ceil($price * (1 + $profit_rate / 100.0));
					$prices[$size] = min($calc_price, $prices[$size] ?? INF);
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

	public function created(VendorPrice $vendorPrice)
	{
		$this->handle($vendorPrice);
	}
	public function updated(VendorPrice $vendorPrice)
	{
		$this->handle($vendorPrice);
	}
	public function deleted(VendorPrice $vendorPrice)
	{
		$this->handle($vendorPrice);
	}
}
