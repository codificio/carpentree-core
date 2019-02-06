<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Exceptions\UnauthorizedException;

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

Route::prefix('api')->namespace('Carpentree\Core\Http\Controllers')->group(function() {

    Route::middleware('auth:api')->group(function() {

        Route::get('try', function (Request $request) {
            return response()->json(array('message' => 'success'));
        })->name('api.try');

        Route::get('users/me', function (Request $request) {
            return new \Carpentree\Core\Http\Resources\UserResource(Auth::user());
        });

        /**
         * Permissions
         */
        Route::get('permissions', 'PermissionController@list')
            ->name('api.permissions.list');

        Route::post('permissions/user/give', 'PermissionController@giveToUser')
            ->name('api.permissions.user.give');

        Route::post('permissions/user/revoke', 'PermissionController@revokeFromUser')
            ->name('api.permissions.user.revoke');

        /**
         * Roles
         */
        Route::get('roles', 'RoleController@list')
            ->name('api.roles.list');

        Route::post('roles/user/assign', 'RoleController@assignToUser')
            ->name('api.permissions.user.give');

        Route::post('roles/user/remove', 'RoleController@removeFromUser')
            ->name('api.permissions.user.revoke');

    });

});
