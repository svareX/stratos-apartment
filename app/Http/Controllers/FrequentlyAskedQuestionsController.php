<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FrequentlyAskedQuestionsController extends Controller
{
    public function __invoke()
    {
        return view('frequently-asked-questions');
    }
}
