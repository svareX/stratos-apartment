<?php

use App\Http\Controllers\Apartment\ApartmentDetailController;
use App\Http\Controllers\HomeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



// Password form routes (not protected by the middleware)
Route::get('/password', function () {
    return view('website-password');
})->name('password.form');

Route::post('/password', function (Request $request) {
    $password = env('WEBSITE_PASSWORD', '');

    if ($password !== '' && $request->input('website_password') === $password) {
        $request->session()->put('website_authenticated', true);

        return redirect()->intended('/');
    }

    return back()->withErrors(['website_password' => 'Invalid password']);
})->name('password.submit');

// The rest of the site's web routes are protected by the website password middleware
Route::middleware([\App\Http\Middleware\RequireWebsitePassword::class])->group(function () {

    Route::get('/', HomeController::class)->name('home');

    Route::get('/apartments/{apartment:slug}', ApartmentDetailController::class)->name('apartments.show');

    Route::get('/about', function () {
        return view('about');
    })->name('about');

    Route::get('/pricing', function () {
        return view('pricing');
    })->name('pricing');

    Route::get('/reservation', function () {
        return view('reservation');
    })->name('reservation');

    Route::get('/reservation/success', function () {
        if (! session('reservation_completed')) {
            return redirect()->route('reservation');
        }

        return view('reservation-success');
    })->name('reservation.success');

    Route::middleware([
        'auth:sanctum',
        config('jetstream.auth_session'),
        'verified',
    ])->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');
    });

    Route::get('/locale/{locale}', function ($locale) {
        $available = ['en', 'cs', 'de'];
        if (! in_array($locale, $available)) {
            abort(404);
        }

        session(['locale' => $locale]);
        app()->setLocale($locale);

        return redirect()->back();
    })->name('locale.switch');
});
