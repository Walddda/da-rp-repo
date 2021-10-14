<?php

namespace App\Http\Controllers;

use App\Models\Beat;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BeatController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {

        // return Inertia::render('Test');

        $beats = Beat::all()->toArray();
        // foreach (Beat::all() as $beat) {
        //     echo $beat->title;
        // }
        // echo "<pre>";
        // var_dump($beats);
        // echo "</pre>";
        // $testObj = '[ { "id": 1, "title": "Lorem Ipsum" }, { "id": 2, "title": "Lorem Ipsum2" } ]';


        return Inertia::render('Test', [
            'beats' => $beats,
        ]);


        // return view('test', ['beats' => $beats]);
    }
}
