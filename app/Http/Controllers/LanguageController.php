<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function edit()
    {
        return view('language.edit');
    }

    public function update(Request $request)
    {
        $language = $request->input('language');
        if ($language && in_array($language, ['en', 'zh'])) {
            Session::put('language', $language);
        }
        return redirect('/');
    }
}
