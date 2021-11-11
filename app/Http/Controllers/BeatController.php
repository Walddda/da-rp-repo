<?php

namespace App\Http\Controllers;

use App\Models\Beat;
use App\Models\File;
use App\Models\Like;
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

        $beats = Beat::with('likes2')->get()->toArray();

        // $testBeats = Beat::find(1);
        // $testBeats = File::with('isBeat', 'isBeat.likes2')->get()->toArray();

        $testBeats = File::with('isBeat', 'isBeat.likes2', 'isBeat.fromUser', 'isBeat.getCover')->has('isBeat')->get()->toArray();
        // $testBeats = Like::whereHasMorph('beats', [Beat::class], function ($query) {
        // $query->where('title', 'tst#title#1');
        // })->get();
        dd($testBeats);
        $txt = '';
        foreach ($testBeats as $beat) {
            foreach ($beat['likes2'] as $like) {
                $txt .= '-Like: ' . $like['email'] . ';';
            }
            // $txt .= '-Like: ' . $like->name . ';';
        }
        // foreach (Beat::all() as $beat) {
        //     echo $beat->title;
        // }
        // echo "<pre>";
        // var_dump($beats);
        // echo "</pre>";
        // $testObj = '[ { "id": 1, "title": "Lorem Ipsum" }, { "id": 2, "title": "Lorem Ipsum2" } ]';


        return Inertia::render('Test', [
            'beats' => $beats,
            'text' => $txt,
        ]);


        // return view('test', ['beats' => $beats]);
    }
}
