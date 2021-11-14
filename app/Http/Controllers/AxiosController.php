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
        $files = File::with('isBeat', 'isBeat.fromUser', 'isBeat.getCover', 'isBeat.likes2')->has('isBeat.fromUser')->get()->toArray();
        return response()->json($files);
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
            'cover' => 'mimes:png,jpg'
        ]);



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



            $beatModel->title = htmlspecialchars($req->input('title'));
            $beatModel->type = htmlspecialchars($req->input('type'));
            $beatModel->tag1 = htmlspecialchars($req->input('tag1'));
            $beatModel->tag2 = htmlspecialchars($req->input('tag2'));
            $beatModel->tag3 = htmlspecialchars($req->input('tag3'));
            $beatModel->tag4 = htmlspecialchars($req->input('tag4'));
            $beatModel->tag5 = htmlspecialchars($req->input('tag5'));
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
