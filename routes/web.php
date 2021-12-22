<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ModalkuController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', [HomeController::class, 'index']);

Route::get('/post/modalku', [ModalkuController::class, 'postModalku']);

Route::get('/testapi', [ModalkuController::class, 'test']);

Route::get('testmodal', 'ModalkuController@olahCallback');
Route::post('callback', 'ModalkuController@getCallback');