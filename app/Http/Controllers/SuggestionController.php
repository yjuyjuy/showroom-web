<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Suggestion;

class SuggestionController extends Controller
{
  public function create()
  {
  	return view('suggestion.create');
  }
	public function store(Request $request)
  {
		$data = $request->validate([
			'category' => 'sometimes|string|in:'.implode(',',$this->categories()),
			'content' => 'required|string|max:255',
		]);
		Suggestion::create($data);
  	return view('suggestion.stored');
  }
	public function categories()
	{
		return [
			'User Interface'
		];
	}
}
