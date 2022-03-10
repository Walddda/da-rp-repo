<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ChangePasswordController extends Controller
{
    public function updatePassword(Request $request)
    {
        // $val = $request->validate([
        //     'password' => 'required|string|min:6|confirmed',
        //     'old_password' => 'required|string|min:6',
        //     //'password_confirmation' => 'required|string|min:6',
        // ]);
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:6|confirmed',
            'old_password' => 'required|string|min:6',
            //'password_confirmation' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => 'not same passwords', 'status' => 'match']);
        }
        $user = User::where('id', '=', auth()->id())->first();
        $credentials = ['email' => $user->email, 'password' => $request->old_password];
        if (Auth::validate($credentials)) {
            $user->update([
                'password' => Hash::make($request->password)
            ]);
            return response()->json(['message' => 'Password updated', 'status' => 'success']);
        } else {
            return response()->json(['message' => 'Password update failed', 'status' => 'fail']);
        }
    }
}
