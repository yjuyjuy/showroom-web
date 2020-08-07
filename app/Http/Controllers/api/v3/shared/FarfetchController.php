<?php

namespace App\Http\Controllers\api\v3\shared;

use App\FarfetchProduct;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FarfetchController extends Controller
{
    public function show(FarfetchProduct $product) {
        return $product->load(['images', 'designer', 'category', 'product']);
    }
}
