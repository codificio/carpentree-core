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

    Route::middleware(['api', 'auth:api', 'verified', 'scope:admin'])->group(function() {

        /**
         * Users
         */
        Route::prefix('users')->group(function() {
            Route::get('/', 'UserController@list')
                ->name('api.users.list');

            Route::get('{id}', 'UserController@get')
                ->name('api.users.get');

            Route::post('/', 'UserController@create')
                ->name('api.users.create');

            Route::patch('/', 'UserController@update')
                ->name('api.users.update');

            Route::delete('{id}', 'UserController@delete')
                ->name('api.users.delete');
        });

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
