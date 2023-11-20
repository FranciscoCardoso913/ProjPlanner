<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaticController extends Controller
{
    public const STATIC_PAGES = ['about-us', 'contacts', 'faq', 'home'];

    public function show(Request $request)
    {

        return response()->view('static_pages.' . $request->path());
    }
}