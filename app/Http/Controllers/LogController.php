<?php

namespace App\Http\Controllers;

use App\Log;
use Illuminate\Http\Request;

class LogController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		$query = Log::orderBy('created_at', 'desc');

		$total_pages = ceil($query->count() / 1000.0);
		$page = min(max($request->query('page', 1), 1), $total_pages);

		$logs = $query->forPage($page, 1000)->get();

		return view('admin.logs', compact('logs', 'total_pages', 'page'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Log  $log
	 * @return \Illuminate\Http\Response
	 */
	public function show(Log $log)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Log  $log
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Log $log)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Log  $log
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Log $log)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Log  $log
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Log $log)
	{
		//
	}
}
