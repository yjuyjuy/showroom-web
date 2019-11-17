<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Suggestion;

class SuggestionController extends Controller
{
  public function index()
  {
    $suggestions = \App\Suggestion::whereNull('archived_at')->latest()->get();
    return view('admin.suggestions', compact('suggestions'));
  }

  public function create()
  {
  	return view('suggestion.create');
  }

  public function store(Request $request)
  {
		\App\Suggestion::create($request->validate([
			'content' => 'required|string',
		]););
  	return view('suggestion.stored');
  }

	public function archive(Suggestion $suggestion)
	{
		$suggestion->archived_at = NOW();
		$suggestion->save();
		return ['success'];
	}
}
