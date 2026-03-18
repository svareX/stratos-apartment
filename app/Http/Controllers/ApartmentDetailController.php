<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use Illuminate\Http\Request;

class ApartmentDetailController extends Controller
{
    /**
     * Load the apartment detail page.
     */
    public function __invoke(Request $request)
    {
        $apartments = Apartment::where('slug', $request->route('apartment'))->where('active', true)->first();

        return view('apartment-detail')->with([
            'apartment' => $apartments,
        ]);
    }
}
