<?php

use App\Http\Controllers\AxiosController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LikeController;


Route::get('beats',  [AxiosController::class, 'getTracks']);
Route::post('upload',  [AxiosController::class, 'upload']);

// Route::middleware(['auth:api'])->group(function () {



//     //other authenticated Routes goes inside this block

// });

// Route::group(['middleware' => ['auth']], function () {
Route::get('beat/like/{id}/{authId}', [LikeController::class, 'likeBeat'])->name('beat.like');
// });

//LIkes

// Route::get('post/like/{id}', ['as' => 'post.like', 'uses' => 'LikeController@likePost']);

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
