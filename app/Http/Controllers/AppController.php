<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppController extends Controller
{
    public function index()
    {
        $links = [
            'ios' => config('app.ios_url'),
        ];
        return view('app.index', compact('links'));
    }
}
