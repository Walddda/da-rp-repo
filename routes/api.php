<?php

use App\Http\Controllers\AxiosController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('beats',  [AxiosController::class, 'getTracks']);
Route::post('upload',  [AxiosController::class, 'upload']);

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
