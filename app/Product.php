<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Arr;

class Product extends Model
{
	/**
	 * The connection name for the model.
	 *
	 * @var string
	 */
	protected $connection = 'mysql';
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'products';

	protected $guarded = [];
	/**
	 * The model's default values for attributes.
	 *
	 * @var array
	 */
	protected $attributes = [];

	// Relationships
	public function category()
	{
		return $this->belongsTo(Category::class);
	}
	public function season()
	{
		return $this->belongsTo(Season::class);
	}
	public function color()
	{
		return $this->belongsTo(Color::class);
	}
	public function brand()
	{
		return $this->belongsTo(Brand::class);
	}
	public function vendors()
	{
		return $this->belongsToMany(Product::class, 'prices', 'product_id', 'vendor_id');
	}
	public function offers()
	{
		return $this->hasMany(OfferPrice::class);
		;
	}
	public function images()
	{
		return $this->hasMany(Image::class)->orderBy('order');
		;
	}
	public function image()
	{
		return $this->hasOne(Image::class)->orderBy('order');
	}
	public function logs()
	{
		return $this->hasMany(Log::class);
	}
	public function retails()
	{
		return $this->hasMany(RetailPrice::class);
	}
	public function prices()
	{
		return $this->hasMany(VendorPrice::class);
	}
	public function taobao_prices()
	{
		return $this->hasMany(TaobaoPrice::class);
	}
	public function farfetch_products()
	{
		return $this->hasMany(FarfetchProduct::class);
	}
	public function end_products()
	{
		return $this->hasMany(EndProduct::class);
	}
	public function ssense_products()
	{
		return $this->hasMany(SsenseProduct::class);
	}
	public function followers()
	{
		return $this->belongsToMany(User::class, 'user_product', 'product_id', 'user_id');
	}
	// Mutators
	public function setCategoryAttribute($value)
	{
		$this->category_id = $value;
	}
	public function setSeasonAttribute($value)
	{
		$this->season_id = $value;
	}
	public function setColorAttribute($value)
	{
		$this->color_id = $value;
	}
	public function setBrandAttribute($value)
	{
		$this->brand_id = $value;
	}
	// Accessors
	public function getOffersToStringAttribute()
	{
		if ($this->offers->isNotEmpty()) {
			$max_offer = max(array_map('max', $this->offers->pluck('prices')->toArray()));
			return $this->displayName().str_replace(' OS', '', ' '.implode('/', array_keys($this->getSizePrice('offer'))))." 调货".$max_offer;
		} else {
			return $this->displayName();
		}
	}
	public function getLinksAttribute()
	{
		if ($this->brand && $this->designer_style_id) {
			return Cache::remember('product-'.$this->id.'-links', 10 * 60, function() {
				$links = [];
				foreach(['Farfetch', 'End', 'Ssense',] as $retailer_name) {
					$cls = '\\App\\'.$retailer_name.'Product';
					foreach($cls::like($this) as $index => $product) {
						$links[$retailer_name.'页面'.($index + 1).($product->colors ?? $product->color ?? '')] = route(strtolower($retailer_name).'.show', ['product' => $product]);
					}
				}
				foreach(['LouisVuitton', 'Dior', 'Gucci', 'OffWhite', 'Balenciaga',] as $brand_name) {
					$cls = '\\App\\'.$brand_name.'Product';
					if ($cls::brand_id == $this->brand_id) {
						foreach($cls::like($this) as $index => $product) {
							$links['官网页面'.($index + 1)] = route(strtolower($brand_name).'.show', ['product' => $product]);
						}
						return $links;
					}
				}
				return $links;
			});
		} else {
			return [];
		}
	}
	// Helpers
	public function getMinPrice($default = false)
	{
		return ($this->retails->isEmpty())? $default : (int)$this->retails->map(function ($retail) {
			return min($retail->prices);
		})->min();
	}
	public function getMinOffer($default = false)
	{
		return ($this->offers->isEmpty())? $default : (int)$this->offers->map(function ($offer) {
			return min($offer->prices);
		})->min();
	}
	public function getPriceAttribute()
	{
		return $this->getMinPrice();
	}
	public function getSizePrice($type = 'retail')
	{
		$data = [];
		if ($type == 'offer') {
			foreach ($this->offers as $offer) {
				foreach ($offer->prices as $size => $price) {
					if (!array_key_exists($size, $data) || $price < $data[$size]['price']) {
						$data[$size] = ['price' => $price, 'vendor' => $offer->vendor->name, 'link' => $offer->vendor->link];
					} elseif ($price == $data[$size]['price']) {
						$data[$size]['vendor'] = $data[$size]['vendor'].' / '.$offer->vendor->name;
					}
				}
			}
		} elseif ($type == 'retail') {
			foreach ($this->retails as $retail) {
				foreach ($retail->prices as $size => $price) {
					if (!array_key_exists($size, $data) || $price < $data[$size]['price']) {
						$data[$size] = ['price' => $price, 'retailer' => $retail->retailer->name, 'link' => $retail->link];
					} elseif ($price == $data[$size]['price']) {
						$data[$size]['retailer'] = $data[$size]['retailer'].' / '.$retail->retailer->name;
					}
				}
			}
		}
		$data = Arr::sort($data, function ($value, $key) {
			if (in_array($key, ['XXS','XS','S','M','L','XL','XXL','XXXL','XXXXL'])) {
				return array_search($key, ['XXS','XS','S','M','L','XL','XXL','XXXL','XXXXL']);
			} else {
				return $key;
			}
		});
		return $data;
	}
	public function getAllPrices()
	{
		$prices = collect();
		foreach ($this->prices as $price) {
			foreach ($price->data as $row) {
				$prices->push([
					'vendor' => $price->vendor->id,
					'size' => $row['size'],
					'cost' => $row['cost'],
					'offer' => $row['offer'],
					'retail' => $row['retail'],
				]);
			}
		}
		return $prices;
	}
	public function displayName()
	{
		$parts = [];
		if ($this->brand) {
			$parts[] = $this->brand->name;
		}
		if ($this->season) {
			$parts[] = $this->season->name;
		}
		if ($this->name_cn) {
			$parts[] = $this->name_cn;
		} else if ($this->name) {
			$parts[] = $this->name;
		}
		if ($this->color) {
			$parts[] = __($this->color->name);
		}
		return implode(' ', $parts);
	}
	public static function generate_id()
	{
		$id = random_int(1000000000, 9999999999);
		while (self::find($id)) {
			$id = random_int(1000000000, 9999999999);
		}
		return $id;
	}
}
