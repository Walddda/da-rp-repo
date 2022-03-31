<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Elliptic\EC;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use kornrunner\Keccak;
use App\Models\User;
use Inertia\Inertia;

class WalletController extends Controller
{
    public function save(Request $request)
    {
        $user = User::where('id', Auth::id());
        // dd($request);

        $user->update([
            'eth_address' => $request->address
        ]);

        return back();
    }
}
