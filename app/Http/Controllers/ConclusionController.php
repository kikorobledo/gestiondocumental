<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConclusionController extends Controller
{
    public function __invoke()
    {
        return view('conclusions.index');
    }
}
