<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DownloadController extends Controller
{
    public function index()
    {
        $links = [
            'Android' => config('app.android_url'),
            'iOS' => config('app.ios_url'),
        ];
        return view('download.index', compact('links'));
    }
}
