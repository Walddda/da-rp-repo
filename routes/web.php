<?php

use App\Http\Controllers\BeatController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\NotificationController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use SebastianBergmann\Environment\Console;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


//Routes for our pages

Route::get('/admin', function () {
    return Inertia::render('Admin', [
        'token' => csrf_token(),
    ]);
})->middleware(['auth', 'verified']);

Route::get('/', function () {
    return Inertia::render('FeedNew', [
        'token' => csrf_token(),
    ]);
});
Route::post('/', function (Request $req) {
    return Inertia::render('FeedNew', [
        'token' => csrf_token(),
        'searchTerm' => $req->input('searchTerm'),
        'givenSearchTerm' => $req->input('searchTerm'),
    ]);
})->name('home');

Route::get('/settings', function () {
    return Inertia::render('Settings', []);
})->middleware(['auth', 'verified']);

Route::get('/profile/{username}', [UserController::class, 'show']);
Route::post('/profile', [UserController::class, 'update'])->name('profile');
Route::post('/wallet', [WalletController::class, 'save'])->name('wallet');

Route::group(['middleware' => ['auth']], function () {
    Route::resource('roles', 'RoleController');
    Route::resource('users', 'UserController');
    Route::resource('blogs', 'BlogController');
});

Route::get('/track/{id}', [BeatController::class, 'singletrack']);


// Routes for E-Mail-Verification
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');


Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');


//TODO in api.php
Route::get('/unreadNotifications', [NotificationController::class, 'unreadNotifications']);
Route::get('/markNotificationsAsRead', [NotificationController::class, 'markAsRead']);

Route::get('/privacy', function () {
    return view('privacyPolicy');
});
Route::get('/terms', function () {
    return view('terms');
});

// Passwort aendern by Gab
//TODO in API??
Route::post('/change-password', [ChangePasswordController::class, 'updatePassword']);




require __DIR__ . '/auth.php';
Route::get('/storage/{any?}', function ($any) {
    return Inertia::render('Error', []);
})->where('any', '(.*)');
Route::get('/{any?}', function ($any) {
    return Inertia::render('Error', []);
})->where('any', '(.*)');