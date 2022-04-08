<?php

namespace App\Http\Controllers;

use App\Models\Entrie;
use App\Models\Tracking;
use App\Models\Conclusion;
use Illuminate\Http\Request;

class EntrieController extends Controller
{
    public function index()
    {
        return view('entries.index');
    }

    public function show(Entrie $entrie, Request $request){

        $trackings = Tracking::with('createdBy', 'files')->where('entrie_id', $entrie->id)->get();

        $conclusions = Conclusion::with('createdBy', 'files')->where('entrie_id', $entrie->id)->get();

        return view('entries.show', compact('entrie', 'trackings', 'conclusions'));
    }
}
