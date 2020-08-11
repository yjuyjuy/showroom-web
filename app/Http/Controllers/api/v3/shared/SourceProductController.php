<?php

namespace App\Http\Controllers\api\v3\shared;

use App\BalenciagaProduct;
use App\DiorProduct;
use App\EndProduct;
use App\FarfetchProduct;
use App\GucciProduct;
use App\Http\Controllers\Controller;
use App\OffWhiteProduct;
use App\SsenseProduct;

class SourceProductController extends Controller
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

    public function gucci(GucciProduct $product)
    {
        return $product->load(['images']);
    }

    public function offwhite(OffWhiteProduct $product)
    {
        return $product->load(['images']);
    }

    public function balenciaga(BalenciagaProduct $product)
    {
        return $product->load(['images']);
    }

    public function ssense(SsenseProduct $product)
    {
        return $product->load(['images']);
    }
}
