<?php

namespace App\Http\Controllers\api\v1;

use App\Device;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DeviceController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		//
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
		$user = auth()->user();
		$data = $request->validate([
			'token' => ['required', 'string'],
			'os' => ['required', 'string', Rule::in(['ios', 'android']) ],
		]);
		$data['user_id'] = $user->id;
		$data['app'] = 'com.yjuyjuy.showroomseller';
		$device = Device::create($data);
		return ['success' => true,];
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Device  $device
	 * @return \Illuminate\Http\Response
	 */
	public function show(Device $device)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Device  $device
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Device $device)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Device  $device
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Device $device)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Device  $device
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Device $device)
	{
		//
	}
}
