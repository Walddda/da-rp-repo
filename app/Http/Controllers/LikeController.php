<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function likeBeat($id, $authId)
    {
        // here you can check if product exists or is valid or whatever

        return $this->handleLike(Beat::class, $id, $authId);
        // return redirect()->back();
    }

    public function handleLike($type, $id, $authId)
    {
        $existing_like = Like::withTrashed()->whereLikeableType($type)->whereLikeableId($id)->whereUserId($authId)->first();

        // return response()->json([
        //     'test' => 'File has been liked.',
        //     'user_id'       =>  $authId,
        //     'likeable_id'   => $id,
        //     'likeable_type' => $type,
        // ]);

        #$req->input('userID')

        // return response()->json([
        //     'success' => 'File has been liked.',
        // ]);

        if (is_null($existing_like)) {
            Like::create([
                'user_id'       => $authId,
                'likeable_id'   => $id,
                'likeable_type' => $type,
            ]);
            $success = 'Like';
            $del = false;
        } else {
            if (is_null($existing_like->deleted_at)) {
                $existing_like->delete();
                $success = 'Like deleted';
                $del = true;
            } else {
                $existing_like->restore();
                $success = 'Like restored';
                $del = false;
            }
        }

        $likeCount = sizeOf(Like::where('likeable_id', '=', $id)->get()->toArray());

        $currentCover = File::with('isBeat', 'isBeat.getCover')
            ->has('isBeat.getCover')
            ->join('beats', function ($join) {
                $join->on('beats.id', '=', 'files.beat_id');
            })
            ->where('beats.id', '=', $id)
            ->get('cover_id')->toArray();

        if ($currentCover[0]['cover_id'] <= 4) {
            switch (true) {
                case ($likeCount <= '0'):
                    $newCover = 1;
                    break;
                case ($likeCount <= '1'):
                    $newCover = 2;
                    break;
                case ($likeCount <= '2'):
                    $newCover = 3;
                    break;
                case ($likeCount <= '3'):
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
                ->where('beats.id', '=', $id)
                ->update([
                    'beats.cover_id' => $newCover,
                ]);
        } else {
            $newCover = 'old';
        }

        return response()->json([
            'success' => $success,
            'del' => $del,
            'cov' => $newCover,
            'count' => $likeCount,
        ]);
    }
}
