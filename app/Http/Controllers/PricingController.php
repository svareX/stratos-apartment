<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use Illuminate\Contracts\View\View;

class PricingController extends Controller
{
    public function __invoke(): View
    {
        $apartments = Apartment::query()
            ->where('active', true)
            ->orderBy('name')
            ->get();

        return view('pricing', [
            'apartments' => $apartments,
        ]);
    }
}
