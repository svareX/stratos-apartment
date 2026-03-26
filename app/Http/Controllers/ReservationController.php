<?php

namespace App\Http\Controllers;

class ReservationController extends Controller
{
    public function __invoke()
    {
        return view('reservation');
    }
}
