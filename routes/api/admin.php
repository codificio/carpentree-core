<?php

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

Route::prefix('api/admin')->namespace('Carpentree\Core\Http\Controllers\Admin')->group(function () {

    Route::middleware(['api', 'auth:api', 'verified'])->group(function() {

        /**
         * Users
         */
        Route::get('user/me', function () {
            return new \Carpentree\Core\Http\Resources\UserResource(Auth::user());
        });

        Route::get('users', 'UserController@list')
            ->name('api.users.list');

        Route::get('user/{id}', 'UserController@get')
            ->name('api.users.get');

        Route::post('user', 'UserController@create')
            ->name('api.users.create');

        Route::delete('user/{id}', 'UserController@delete')
            ->name('api.users.delete');

        /**
         * Users permissions
         */
        Route::post('user/{id}/permissions/sync', 'PermissionController@syncWithUser')
            ->name('api.users.permissions.sync');

        Route::post('user/{id}/permissions/revoke', 'PermissionController@revokeFromUser')
            ->name('api.users.permissions.revoke');

        /**
         * Users roles
         */
        Route::post('user/{id}/roles/sync', 'RoleController@syncWithUser')
            ->name('api.users.roles.sync');

        Route::post('user/{id}/roles/revoke', 'RoleController@revokeFromUser')
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
