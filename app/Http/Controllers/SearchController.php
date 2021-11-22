<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Beat;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    //
    public function search(Request $request)
    {
        $res = Beat::where('title', 'LIKE', '%' . $request->keywords . '%')
            ->join('files', function ($join) use ($request) {
                $join->on('beats.id', '=', 'files.beat_id');
            })
            ->select('*')
            ->get();
        return response()->json($res);
    }

    public function result(Request $request)
    {
        return Inertia::render('Result', []);
    }
}
