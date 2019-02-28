<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Carpentree\Core\Http\Resources\UserResource;

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

Route::prefix('api')->group(function () {

    Route::middleware(['api', 'auth:api'])->group(function() {

        Route::get('user/me', function() {
            return UserResource::make(Auth::user());
        })->name('api.user.me');

        Route::get('locales', 'Carpentree\Core\Http\Controllers\LocaleController@all')
            ->name('api.locales.all');

    });

});
