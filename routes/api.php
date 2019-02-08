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
         * Users permissions
         */
        Route::post('user/{id}/permissions/sync', 'PermissionController@syncWithUser')
            ->name('api.user.permissions.sync');

        Route::post('user/{id}/permissions/revoke', 'PermissionController@revokeFromUser')
            ->name('api.user.permissions.revoke');

        /**
         * Users roles
         */
        Route::post('user/{id}/roles/sync', 'RoleController@syncWithUser')
            ->name('api.user.roles.sync');

        Route::post('user/{id}/roles/revoke', 'RoleController@revokeFromUser')
            ->name('api.user.roles.revoke');

        /**
         * Permissions
         */
        Route::get('permissions', 'PermissionController@list')
            ->name('api.permissions.list');

        /**
         * Roles
         */
        Route::get('roles', 'RoleController@list')
            ->name('api.roles.list');

    });

});
