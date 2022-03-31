<?php

namespace App\Http\Controllers;

use App\Models\Beat;
use App\Models\Cover;
use App\Models\File;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Notifications\PaymentNotification;
use App\Notifications\DownloadCountNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Redirect;

class AxiosController extends Controller
{
    public function getTracks(Request $request)
    {
        // dd(json_decode($request->profile)->id);
        // var_dump(sizeOf(json_decode($request->profile)));
        if (strlen($request->profile) > 2) {
            // dd('yes');
            // dd(json_decode($request->profile));
            if (json_decode($request->profile)->transact) {

                $res = File::with('isBeat', 'isBeat.fromUser', 'isBeat.transactions', 'isBeat.getCover', 'isBeat.likes2')
                    ->has('isBeat.fromUser')
                    ->join('beats', function ($join) {
                        $join->on('beats.id', '=', 'files.beat_id');
                    })
                    ->join('transactions', function ($join) {
                        $join->on('beats.id', '=', 'transactions.beat_id');
                    })
                    ->where('transactions.buyer_id', '=', json_decode($request->profile)->id)
                    ->orderBy('beats.created_at', 'desc')
                    ->get()
                    ->toArray();
            } else {
                $res = File::with('isBeat', 'isBeat.fromUser', 'isBeat.getCover', 'isBeat.likes2')
                    ->has('isBeat.fromUser')
                    ->join('beats', function ($join) {
                        $join->on('beats.id', '=', 'files.beat_id');
                    })
                    ->where('beats.user_id', '=', json_decode($request->profile)->id)
                    ->orderBy('beats.created_at', 'desc')
                    ->get()
                    ->toArray();
            }
            // dd($res);
            return response()->json($res);
        } else if ($request->id){
            $res = File::with('isBeat', 'isBeat.fromUser', 'isBeat.getCover', 'isBeat.likes2')
            ->has('isBeat.fromUser')
            ->join('beats', function ($join) {
                $join->on('beats.id', '=', 'files.beat_id');
            })
            ->where('beats.id', '=', $request->id)
            ->orderBy('beats.created_at', 'desc')
            ->get()
            ->toArray();
            return response()->json($res);
        } else if (!$request->keywords) {
            $files = File::with('isBeat', 'isBeat.fromUser', 'isBeat.getCover', 'isBeat.likes2')->has('isBeat.fromUser')
                ->orderBy('created_at', 'desc')
                ->get()->toArray();
            return response()->json($files);
        } else if ($request->keywords) {
            $res = File::with('isBeat', 'isBeat.fromUser', 'isBeat.getCover', 'isBeat.likes2')

                ->join('beats', function ($join) {
                    $join->on('beats.id', '=', 'files.beat_id');
                })
                // ->join('users', function ($join) {
                //     $join->on('beats.user_id', '=', 'users.id');
                // })
                ->has('isBeat.fromUser')
                ->where('beats.title', 'LIKE', '%' . $request->keywords . '%')
                ->orWhere('beats.description', 'LIKE', '%' . $request->keywords . '%')
                ->orWhere('beats.tag1', 'LIKE', '%' . $request->keywords . '%')
                ->orWhere('beats.tag2', 'LIKE', '%' . $request->keywords . '%')
                ->orWhere('beats.tag3', 'LIKE', '%' . $request->keywords . '%')
                ->orWhere('beats.tag4', 'LIKE', '%' . $request->keywords . '%')
                ->orWhere('beats.tag5', 'LIKE', '%' . $request->keywords . '%')
                // ->orWhere('isBeat.fromUser.username', 'LIKE', '%' . $request->keywords . '%')
                // ->orWhere('users.username', 'LIKE', '%' . $request->keywords . '%')
                ->orderBy('beats.created_at', 'desc')
                ->get()
                ->toArray();
            return response()->json($res);
        }
    }
    public function getUser(Request $request)
    {
        // dd(auth()->id());
        $users = User::where('id', '!=', auth()->id())->get();
        return response()->json($users);
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
        // $inputFile = $req->file('beat');
        // if (!$inputFile->isValid()) {
            // dd($inputFile->getErrorMessage());
        // }
        // dd($req->file('beat'));
        // dd($req);
        // $req->validate([
        //     'beat' => 'required|max:10240',
        //     'cover' => 'mimes:png,jpg',
        //     'title' => 'required|max:255',
        //     'ethPrice' => array('required', 'digits_between:1,10'),
        //     'type' => array('required', 'regex:/(sample|beat)/'),
        //     'bpm' => array('required', 'regex:/\d+/'),
        // ]);
        $validator = Validator::make($req->all(), [
            'beat' => 'required|max:10240',
            'cover' => 'mimes:png,jpg',
            'title' => 'required|max:255',
            'selectedKey' => 'required|max:3',
            'ethPrice' => array('required', 'digits_between:1,10'),
            'type' => array('required', 'regex:/(sample|beat)/'),
            'bpm' => array('required', 'regex:/\d+/'),
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ]);
        }


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

            $exiFile = File::where('beat_id', $beat_id)->first();
            if ($exiFile) {
                $exiFile->delete();
            }
            $path_name = time() . '_' . $req->input('userID') . '_' . rand(1000000, 9999999) . '_' . $beat_id;

            $filePath = $req->file('beat')->storePubliclyAs('uploads', $path_name . '.' . $req->file('beat')->getClientOriginalExtension(), 'public');
            // $filePath = $req->file('beat')->storePubliclyAs('uploads', $path_name . '.png', 'public');

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
            $beatModel->feature = htmlspecialchars($req->input('feature'));


            // $tagnames = array('tag1');
            foreach ($tagsSplit as $key => $value) {
                $beatModel->{'tag' . $key + 1} = $value;
            }
            $beatModel->key = htmlspecialchars($req->input('selectedKey'));
            $beatModel->description = htmlspecialchars($req->input('description'));
            $beatModel->bpm = htmlspecialchars($req->input('bpm'));
            $beatModel->price = htmlspecialchars($req->input('ethPrice'));
            $beatModel->archive = 0;
            $beatModel->user_id = htmlspecialchars($req->input('userID'));
            $beatModel->cover_id = $coverId;
            $beatModel->length = $req->input('length');


            $fileModel->save();
            // dd('test');
            $beatModel->save();
            return response()->json([
                'id' => $beat_id,
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

    public function edit($id, Request $req)
    {

        $errorMsg = [
            "titleMpty" => 'Please set a title',
            "authMpty" => 'Please log in?',
            "type" => 'Please select a Type',
            "bpm" => 'Please select a BPM',
            "other" => 'Something other went wrong',
        ];
        $errorKey = 'other';

        $req->validate([
            'title' => 'required|max:255',
            'type' => array('required', 'regex:/(sample|beat)/'),
            'bpm' => array('required', 'regex:/\d+/'),
        ]);

        $tagsSplit = explode(',', $req->input('tagsArr'));

        foreach ($tagsSplit as $key => $value) {
            Beat::where('id', $id)
                ->update(['tag' . $key + 1 => $value]);
        }
        for ($i = 5; $i > sizeOf($tagsSplit); $i--) {
            Beat::where('id', $id)
                ->update(['tag' . $i => null]);
        }
        // dd(sizeOf($tagsSplit));
        Beat::where('id', $id)
            ->update([
                'title' => htmlspecialchars($req->input('title')),
                'type' => htmlspecialchars($req->input('type')),
                'feature' => htmlspecialchars($req->input('feature')),
                'key' => htmlspecialchars($req->input('selectedKey')),
                'description' => htmlspecialchars($req->input('description')),
                'bpm' => $req->input('bpm'),
            ]);

        // 'type' => 'required|regex:(sample|beat)',
        // 'bpm' => 'required|regex:(\d*)',



        return response()->json([
            'success' => 'Track saved',
        ]);
    }

    public function transaction(Request $req)
    {
        $transaction = new Transaction();

        $transaction->hash = $req->input('hash');
        $transaction->buyer_id = $req->input('buyer_id');
        $transaction->seller_id = $req->input('seller_id');
        $transaction->beat_id = $req->input('beat_id');
        $transaction->save();

        $receiver = User::where('id', '=', $req->input('seller_id'))->first();
        $user = User::where('id', '=', $req->input('buyer_id'))->first();
        $beat = Beat::where('id', '=', $req->input('beat_id'))->first();

        Notification::send($receiver, new PaymentNotification($user, $beat));

        return response()->json([
            'success' => 'Transaction saved',
        ]);
    }

    public function getTransactions(Request $request)
    {
        $transactions = DB::table('transactions')->get();
        return response()->json($transactions);
    }
    public function getTransaction(Request $request)
    {
        // dd($request->user);
        $transaction = DB::table('transactions')
        ->where('beat_id', $request->beat)
        ->where('buyer_id', $request->user)
        ->get();
        return response()->json($transaction);
    }

    public function downloadCounter(Request $request)
    {
        $downCount = sizeOf(Transaction::where('beat_id', '=', $request->input('beat_id'))->get()->toArray());

        Beat::where('id', $request->input('beat_id'))
            ->update(['download_count' => $downCount]);


        $receiver = User::where('id', '=', $request->input('seller_id'))->first();

        $beat = Beat::where('id', $request->input('beat_id'))->first();


        if ($beat['cover_id'] <= 4) {
            switch (true) {
                case ($downCount <= '9'):
                    $newCover = 1;
                    break;
                case ($downCount <= '99'):
                    $newCover = 2;
                    break;
                case ($downCount <= '999'):
                    $newCover = 3;
                    break;
                case ($downCount >= '1000'):
                    $newCover = 4;
                    break;
                default:
                    $newCover = 1;
                    break;
            }
            $currFile = File::with('isBeat', 'isBeat.getCover')
                ->has('isBeat.getCover')
                ->join('beats', function ($join) {
                    $join->on('beats.id', '=', 'files.beat_id');
                })
                ->where('beats.id', '=', $request->input('beat_id'))
                ->update([
                    'beats.cover_id' => $newCover,
                ]);
        } else {
            $newCover = 'old';
        }

        if ($downCount == 10) {
            Notification::send($receiver, new DownloadCountNotification($beat));
        } elseif ($downCount == 100) {
            Notification::send($receiver, new DownloadCountNotification($beat));
        } elseif ($downCount == 200) {
            Notification::send($receiver, new DownloadCountNotification($beat));
        } elseif ($downCount == 500) {
            Notification::send($receiver, new DownloadCountNotification($beat));
        } elseif ($downCount == 1000) {
            Notification::send($receiver, new DownloadCountNotification($beat));
        }

        return response()->json([
            'success' => 'Download saved',
            'cov' => $newCover,
        ]);
    }

    public function deleteTrack($id, Request $request)
    {
        Beat::where('id', $id)
            ->delete();
    }

    public function unreadNotifications()
    {
        $unreadNotifications = Auth::user()->unreadNotifications;
        return response()->json($unreadNotifications);
    }
}
