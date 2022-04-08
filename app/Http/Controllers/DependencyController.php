<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DependencyController extends Controller
{
    public function __invoke()
    {
        return view('dependencies.index');
    }
}
