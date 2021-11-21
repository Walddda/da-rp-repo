<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Beat;

class SearchController extends Controller
{
    //
    public function search(Request $request) {
        $search = $request->get('q');
        return Beat::where('title', 'like', '%'.$search.'%')->get();
    }
}
