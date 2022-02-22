<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function unreadNotifications()
    {
        $unreadNotifications = Auth::user()->unreadNotifications;
        return response()->json($unreadNotifications);
    }

    public function markAsRead()
    {
        Auth::user()->notifications->markAsRead();
        return response()->json('success');
    }
}
