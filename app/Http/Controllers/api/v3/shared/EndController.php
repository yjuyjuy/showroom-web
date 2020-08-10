<?php

namespace App\Http\Controllers\api\v3\shared;

use App\EndProduct;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EndController extends Controller
{
    public function show(EndProduct $product)
    {
        return $product->load(['images']);
    }
}
