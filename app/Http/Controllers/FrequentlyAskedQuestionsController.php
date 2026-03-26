<?php

namespace App\Http\Controllers;

class FrequentlyAskedQuestionsController extends Controller
{
    public function __invoke()
    {
        return view('frequently-asked-questions');
    }
}
