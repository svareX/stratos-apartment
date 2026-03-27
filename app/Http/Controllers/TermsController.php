<?php

namespace App\Http\Controllers;

class TermsController extends Controller
{
    public function __invoke()
    {
        return view('terms');
    }
}