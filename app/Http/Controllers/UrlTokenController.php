<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UrlTokenController extends Controller
{
  public function find($token)
  {
		abort(404);
  }
}
