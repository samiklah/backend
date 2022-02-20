<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
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
Route::get('/', function () {
    $users = DB::select("select * from users");
        return 'hi';
});

Route::get('/support', function () {
    return view("welcome");
});
Route::get('/eula', function () {
    return view("termsOfUse");
});
Route::get('/support/trem-global', function () {
    return view("welcome-trem");
});
Route::get('/support/coinx', function () {
    return view("welcome-bitmex");
});
Route::get('/privacy-policy', function () {
    return view("privacy");
});
Route::get('/privacy-policy/trem-global', function () {
    return view("privacy-trem");
});
Route::get('/privacy-policy/coinx', function () {
    return view("privacy-bitmex");
});