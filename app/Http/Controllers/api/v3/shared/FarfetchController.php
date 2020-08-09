<?php

namespace App\Http\Controllers\api\v3\shared;

use App\FarfetchProduct;
use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Http\Request;

class FarfetchController extends Controller
{
    public function show(FarfetchProduct $product) {
        return $product->load(['images', 'designer', 'category', 'product']);
    }

    public function like(Product $product) {
        return FarfetchProduct::like($product);
    }
}
