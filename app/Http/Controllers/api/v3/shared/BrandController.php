<?php

namespace App\Http\Controllers\api\v3\shared;

use App\Brand;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index()
    {
        $query = Brand::query();
        $query->with(['product', 'product.image']);
        return $query->get();
    }
}
