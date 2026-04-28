<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Schema;

class PricingController extends Controller
{
    public function __invoke(): View
    {
        // Order by the locale-specific name column (e.g. name_en, name_cs, name_de).
        // Fall back to `name_en` or `id` if the expected column doesn't exist.
        $locale = app()->getLocale() ?: config('app.locale', 'en');
        $col = 'name_'.$locale;

        if (! Schema::hasColumn('apartments', $col)) {
            $col = 'name_en';
        }

        if (! Schema::hasColumn('apartments', $col)) {
            $apartments = Apartment::where('active', true)->orderBy('id')->get();
        } else {
            $apartments = Apartment::where('active', true)->orderBy($col, 'asc')->get();
        }

        return view('pricing', [
            'apartments' => $apartments,
        ]);
    }
}
