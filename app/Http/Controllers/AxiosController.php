<?php

namespace App\Http\Controllers;

use App\Models\Beat;
use App\Models\Cover;
use App\Models\File;
use Illuminate\Http\Request;

class AxiosController extends Controller
{
    public function getTracks(Request $request)
    {
        if (!$request->keywords) {
            $files = File::with('isBeat', 'isBeat.fromUser', 'isBeat.getCover', 'isBeat.likes2')->has('isBeat.fromUser')->get()->toArray();
            return response()->json($files);
        } else {
            $res = File::with('isBeat', 'isBeat.fromUser', 'isBeat.getCover', 'isBeat.likes2')
                ->has('isBeat.fromUser')
                ->join('beats', function ($join) {
                    $join->on('beats.id', '=', 'files.beat_id');
                })
                ->where('beats.title', 'LIKE', '%' . $request->keywords . '%')
                ->get()
                ->toArray();
            // $res = Beat::where('title', 'LIKE', '%' . $request->keywords . '%')
            //     ->join('files', function ($join) use ($request) {
            //         $join->on('beats.id', '=', 'files.beat_id');
            //     })
            //     ->get()
            //     ->toArray();
            return response()->json($res);
        }
    }

    public function search()
    {
    }

    public function likeCount($id, Request $request)
    {
        $files = File::with('isBeat', 'isBeat.likes2')->has('isBeat')->where('id', '=', $id)->get();
        return response()->json($files);
    }

    public function upload(Request $req)
    {
        $errorMsg = [
            "titleMpty" => 'Please set a title',
            "authMpty" => 'Please log in?',
            "mp3" => 'Please upload a mp3 or wav File',
            "type" => 'Please select a Type',
            "bpm" => 'Please select a BPM',
            "bpm" => 'Please select a BPM',
            "other" => 'Something other went wrong',
        ];
        $errorKey = 'other';
        // dd($req);
        $req->validate([
            'beat' => 'required|max:10240',
            'cover' => 'mimes:png,jpg',
            'title' => 'required|max:255',
            'type' => array('required', 'regex:/(sample|beat)/'),
            'bpm' => array('required', 'regex:/\d+/'),
        ]);

        // 'type' => 'required|regex:(sample|beat)',
        // 'bpm' => 'required|regex:(\d*)',


        $fileModel = new File;
        $coverModel = new Cover;
        $beatModel = new Beat;
        $filePath = '';

        if (!$req->input('title')) {
            $errorKey = 'titleMpty';
        } else if (!auth()->id() && !$req->input('userID')) {
            $errorKey = 'authMpty';
        } else if ($req->hasfile('beat')) {
            if ($req->file('beat')->getClientOriginalExtension() != 'mp3' && $req->file('beat')->getClientOriginalExtension() != 'wav') {
                return response()->json([
                    'error' => $errorMsg['mp3'],
                ]);
            }

            // return var_dump($req->file('beat')->getClientOriginalExtension());
            $beat_id = $beatModel->getNextId();
            $path_name = time() . '_' . $req->input('userID') . '_' . rand(1000000, 9999999) . '_' . $beat_id;

            $filePath = $req->file('beat')->storePubliclyAs('uploads', $path_name . '.' . $req->file('beat')->getClientOriginalExtension(), 'public');

            $fileModel->name = time() . '_' . $req->file('beat')->getClientOriginalName();
            $fileModel->file_path = '/storage/' . $filePath;
            $fileModel->beat_id = $beat_id;

            $coverId = '';

            if ($req->hasfile('cover')) {

                $coverPath = $req->file('cover')->storePubliclyAs('covers', $path_name . '.' . $req->file('cover')->getClientOriginalExtension(), 'public');

                $coverModel->name = time() . '_' . $req->file('cover')->getClientOriginalName();
                $coverModel->cover_path = '/storage/' . $coverPath;
                $coverModel->save();

                $coverId = $coverModel->id();
            } else {
                $coverId = 1;
            }

            $tagsSplit = explode(',', $req->input('tagsArr'));
            // dd(explode(',', $req->input('tagsArr')));

            $beatModel->title = htmlspecialchars($req->input('title'));
            $beatModel->type = htmlspecialchars($req->input('type'));

            // $tagnames = array('tag1');
            foreach ($tagsSplit as $key => $value) {
                $beatModel->{'tag' . $key + 1} = $value;
            }
            $beatModel->key = htmlspecialchars($req->input('selectedKey'));
            $beatModel->description = htmlspecialchars($req->input('description'));
            $beatModel->bpm = htmlspecialchars($req->input('bpm'));
            $beatModel->price = 1;
            $beatModel->archive = 0;
            $beatModel->user_id = htmlspecialchars($req->input('userID'));
            $beatModel->cover_id = $coverId;

            $fileModel->save();
            $beatModel->save();
            return response()->json([
                'path' => $filePath,
                'success' => 'File has been uploaded.',
            ]);
        } else {

            return response()->json([
                'path' => $filePath,
                'error' => $errorMsg[$errorKey],
            ]);
        }
    }
}
