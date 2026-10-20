<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;

class CountryController extends Controller
{
 
public function index()
{
    $countries = Country::orderBy('short_name')->get();
    return view('pages.welcome', compact('countries'));
}

public function getJson()
{
    $countries = Country::orderBy('short_name')->get(['country_id', 'short_name']);
    return response()->json([
        'success' => true,
        'data' => $countries
    ]);
}


}
