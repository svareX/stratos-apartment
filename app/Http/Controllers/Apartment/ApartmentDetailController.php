<?php

namespace App\Http\Controllers\Apartment;

use App\Http\Controllers\Controller;
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

        $heroImages = [
            'https://picsum.photos/1920/1280?random=7',
            'https://picsum.photos/1920/1280?random=8',
            'https://picsum.photos/1920/1280?random=9',
        ];

        $apartmentImages = [
            'https://picsum.photos/1200/900?random=1',
            'https://picsum.photos/1200/900?random=2',
            'https://picsum.photos/1200/900?random=3',
            'https://picsum.photos/1200/900?random=4',
            'https://picsum.photos/1200/900?random=5',
            'https://picsum.photos/1200/900?random=6',
        ];

        return view('apartment.detail')->with([
            'apartment' => $apartments,
            'heroImages' => $heroImages,
            'apartmentImages' => $apartmentImages,
        ]);
    }
}
