<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

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
    if (!session('reservation_completed')) {
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