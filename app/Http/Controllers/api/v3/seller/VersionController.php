<?php

namespace App\Http\Controllers\api\v3\seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VersionController extends Controller
{
    public function __invoke() {
        return [
            'min' => '0.3.4',
            'current' => '0.3.4',
        ];
    }
}
