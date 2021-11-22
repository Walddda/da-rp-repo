<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;



class ProfileController extends Controller
{
    public function show()
    {
        return Inertia::render('Profile', [
            'logedIn' => Auth::id(),
        ]);
    }
}
