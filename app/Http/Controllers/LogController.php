<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Log;
class LogController extends Controller
{
	public function index(){
		$logs = \App\Log::orderBy('created_at','desc')->limit(100)->get()->load(['product' => function($query) {
			$query->withTrashed();
		},'price' => function($query) {
			$query->withTrashed();
		},'price.vendor']);
		return view('logs.index',compact(['logs',]));
	}
	public function destroy(Log $log){
		$log->delete();
	}
}
