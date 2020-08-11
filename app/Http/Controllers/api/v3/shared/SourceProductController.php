<?php

namespace App\Http\Controllers\api\v3\shared;

use App\DiorProduct;
use App\EndProduct;
use App\FarfetchProduct;
use App\Http\Controllers\Controller;

class DiorController extends Controller
{
    public function farfetch(FarfetchProduct $product)
    {
        return $product->load(['images', 'designer', 'category']);
    }
    
    public function end(EndProduct $product)
    {
        return $product->load(['images']);
    }

    public function dior(DiorProduct $product)
    {
        return $product->load(['images']);
    }
}
