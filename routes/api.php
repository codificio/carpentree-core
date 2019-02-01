<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

Route::prefix('api')->group(function() {

    Route::middleware('auth:api')->group(function() {

        Route::get('try', function (Request $request) {
            return response()->json(array('message' => 'success'));
        })->name('api.try');

        Route::get('users/me', function (Request $request) {
            return new \Carpentree\Core\Http\Resources\UserResource(Auth::user());
        });

    });

});
