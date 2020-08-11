<?php

namespace App\Http\Controllers\api\v3\shared;

use App\BalenciagaProduct;
use App\DiorProduct;
use App\EndProduct;
use App\FarfetchProduct;
use App\GucciProduct;
use App\OffWhiteProduct;
use App\Product;
use App\SsenseProduct;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{

    public function similar(Product $product)
    {
        return Cache::remember("similar-products-of-{$product->id}-{$product->designer_style_id}", 60 * 15, function () use ($product) {
            $similar_products = [
                'farfetch' => FarfetchProduct::like($product),
                'end' => EndProduct::like($product),
                'ssense' => SsenseProduct::like($product),
                'dior' => [],
                'gucci' => [],
                'off-white' => [],
                'balenciaga' => [],
            ];
            if ($product->brand_id) {
                if ($product->brand_id == DiorProduct::brand_id) {
                    $similar_products['dior'] = DiorProduct::like($product);
                } else if ($product->brand_id == GucciProduct::brand_id) {
                    $similar_products['gucci'] = GucciProduct::like($product);
                } else if ($product->brand_id == BalenciagaProduct::brand_id) {
                    $similar_products['balenciaga'] = BalenciagaProduct::like($product);
                } else if ($product->brand_id == OffWhiteProduct::brand_id) {
                    $similar_products['off-white'] = OffWhiteProduct::like($product);
                } else if ($product->brand_id == BalenciagaProduct::brand_id) {
                    $similar_products['balenciaga'] = BalenciagaProduct::like($product);
                }
            }
            return $similar_products;
        });
    }
}
