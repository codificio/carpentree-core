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

Route::prefix('api')->namespace('Carpentree\Core\Http\Controllers')->group(function() {

    Route::middleware(['auth:api', 'verified'])->group(function() {

        Route::get('try', function (Request $request) {
            return response()->json(array('message' => 'success'));
        })->name('api.try');

        Route::get('users/me', function (Request $request) {
            return new \Carpentree\Core\Http\Resources\UserResource(Auth::user());
        });

        /**
         * Users
         */
        Route::get('users', 'UserController@list')
            ->name('api.users.list');

        Route::get('users/{id}', 'UserController@get')
            ->name('api.users.get');

        /**
         * Users permissions
         */
        Route::post('users/{id}/permissions/sync', 'PermissionController@syncWithUser')
            ->name('api.users.permissions.sync');

        Route::post('users/{id}/permissions/revoke', 'PermissionController@revokeFromUser')
            ->name('api.users.permissions.revoke');

        /**
         * Users roles
         */
        Route::post('users/{id}/roles/sync', 'RoleController@syncWithUser')
            ->name('api.users.roles.sync');

        Route::post('users/{id}/roles/revoke', 'RoleController@revokeFromUser')
            ->name('api.users.roles.revoke');

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
