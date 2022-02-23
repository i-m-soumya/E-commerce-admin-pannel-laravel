<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Authentication;
use RealRashid\SweetAlert\Facades\Alert;

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

//UNPROTECTED ROUTES [Login not required]



//PROTECTED ROUTES [Login required]
Route::middleware('auth:api')->group(function () {
    Route::get('/user', function (Request $request) { return $request->user(); });
});

