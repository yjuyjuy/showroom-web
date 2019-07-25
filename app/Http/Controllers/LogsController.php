<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LogController extends Controller
{
	$logs = \App\Log::all()->load(['product']);
  return view('logs.index',compact(['logs',]);
}
