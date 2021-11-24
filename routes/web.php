<?php

use App\Http\Controllers\BeatController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FileUpload;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

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

// Routes for File Upload
Route::get('/upload', [FileUpload::class, 'createForm']);

Route::post('/upload', [FileUpload::class, 'fileUpload'])->name('fileUpload');

//Routes for our pages

Route::get('/feed', [FeedController::class, 'show']);
// Route::get('/fed', [FeedController::class, 'showAxios']);
Route::get('/', function () {
    return Inertia::render('Feed', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'token' => csrf_token(),
    ]);
});
Route::post('/', function (Request $req) {
    return Inertia::render('Feed', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'token' => csrf_token(),
        'searchTerm' => $req->input('searchTerm'),
    ]);
});
// ->middleware('guest')
// ->name('register');

Route::get('/feed', [FeedController::class, 'show']);


Route::get('/myprofile', [UserController::class, 'show']);
Route::post('/myprofile', [UserController::class, 'update'])->name('myprofile')->middleware(['auth', 'verified']);


// Routes for Auth-Pages
Route::get('/old', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/wallet', function () {
    return Inertia::render('Wallet', []);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/test', [BeatController::class, 'show']);

Route::group(['middleware' => ['auth']], function () {
    Route::resource('roles', 'RoleController');
    Route::resource('users', 'UserController');
    Route::resource('blogs', 'BlogController');
});


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

Route::get('/profile', function () {
    // Only verified users may access this route...
})->middleware('verified');


// Route::get('post/like/{id}', ['as' => 'post.like', 'uses' => 'LikeController@likePost']);

require __DIR__ . '/auth.php';
