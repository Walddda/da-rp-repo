<?php

namespace App\Http\Controllers;

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
        } else {
            if (is_null($existing_like->deleted_at)) {
                $existing_like->delete();
                return response()->json([
                    'success' => 'Like deleted.',
                    'del' => true
                ]);
            } else {
                $existing_like->restore();
                return response()->json([
                    'success' => 'Like restored.',
                    'del' => false
                ]);
            }
        }

        return response()->json([
            'success' => 'Like.',
            'del' => false
        ]);
    }
}
