<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use Illuminate\Contracts\View\View;

class PackagesController extends Controller
{
    public function __invoke(): View
    {
        $apartments = Apartment::query()
            ->where('active', true)
            ->with(['packages', 'photosMain'])
            ->get();

        return view('packages', [
            'apartments' => $apartments,
        ]);
    }
}
