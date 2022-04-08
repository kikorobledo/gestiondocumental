<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function __invoke()
    {
        return view('permissions.index');
    }
}
