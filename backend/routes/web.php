<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/{shortened}', RedirectShortenedController::class);

Route::post('login', 'SessionAuthController@login');
Route::post('logout', 'SessionAuthController@logout');
//Route::get('/auth/user', 'AuthController@me')->middleware('auth:sanctum');
