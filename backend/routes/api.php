<?php

use App\Http\Controllers\Api\ShortUrlController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('token', 'Api\AuthController@token');
Route::get('user', 'Api\AuthController@me')->middleware('auth:sanctum');

Route::get('short-url', [ShortUrlController::class, 'index'])->middleware('auth:sanctum');
Route::post('short-url', [ShortUrlController::class, 'store']);
