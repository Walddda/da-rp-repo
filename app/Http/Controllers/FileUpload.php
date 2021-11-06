<?php

namespace App\Http\Controllers;

use App\Models\Cover;
use Illuminate\Http\Request;
use App\Models\File;
use App\Models\Beat;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class FileUpload extends Controller
{
    public function createForm()
    {
        // return view('file-upload');
        return Inertia::render('FileUpload', [
            'route' => route('fileUpload'),
            'token' => csrf_token(),
            'logedin' => Auth::id(),
        ]);
    }

    public function fileUpload(Request $req)
    {
        $req->validate([
            'file' => 'required|mimes:mp3|max:10240',
            'file2' => 'mimes:png,jpg'
        ]);
        // $req->validate([
        //     'file2' => 'required|mimes:png|max:10240'
        // ]);

        $fileModel = new File;
        $coverModel = new Cover;
        $beatModel = new Beat;

        // dd($req);

        if ($req->hasfile('file') && $req->hasfile('file2')) {
            $beat_id = $beatModel->getNextId();

            $fileName = time() . '_' . $req->file('file')->getClientOriginalName();
            $filePath = $req->file('file')->storePubliclyAs('uploads', $fileName, 'public');

            $fileModel->name = htmlspecialchars(time() . '_' . $req->file('file')->getClientOriginalName());
            $fileModel->file_path = '/storage/' . htmlspecialchars($filePath);
            $fileModel->beat_id = $beat_id;

            $coverName = htmlspecialchars(time() . '_' . $req->file('file2')->getClientOriginalName());
            $coverPath = $req->file('file2')->storePubliclyAs('covers', $coverName, 'public');

            $coverModel->name = htmlspecialchars(time() . '_' . $req->file('file2')->getClientOriginalName());
            $coverModel->cover_path = '/storage/' . htmlspecialchars($coverPath);
            $coverModel->beat_id = $beat_id;

            $beatModel->title = htmlspecialchars($req->input('beatTitle'));
            $beatModel->type = htmlspecialchars('x');
            $beatModel->tag1 = htmlspecialchars('x');
            $beatModel->description = htmlspecialchars('x');
            $beatModel->bpm = 1;
            $beatModel->key_signatures_id = 1;
            $beatModel->price = 1;
            $beatModel->archive = 0;
            $beatModel->user_id = auth()->id();

            $fileModel->save();
            $coverModel->save();
            $beatModel->save();

            return Inertia::render('FileUpload', [
                'success' => 'File has been uploaded.',
                'file1' => htmlspecialchars($fileName),
                'file2' => htmlspecialchars($coverName),
                'token' => csrf_token(),
                'logedin' => Auth::id(),
            ]);
        }
    }
}
