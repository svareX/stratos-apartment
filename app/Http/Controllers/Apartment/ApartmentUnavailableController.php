<?php

namespace App\Http\Controllers\Apartment;

use App\Http\Controllers\Controller;
use App\Models\Apartment;
use Illuminate\Http\Request;

class ApartmentUnavailableController extends Controller
{
    public function __invoke(Request $request)
    {
        $apartment = Apartment::where('slug', $request->route('apartment'))->first();

        return view('apartment.unavailable')->with([
            'apartment' => $apartment,
        ]);
    }
}
