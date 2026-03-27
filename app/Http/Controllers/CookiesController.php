<?php

namespace App\Http\Controllers;

class CookiesController extends Controller
{
    public function __invoke()
    {
        return view('cookies');
    }
}
