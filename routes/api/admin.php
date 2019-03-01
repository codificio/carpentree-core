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

Route::prefix('api/admin')
    ->name('api.admin.')
    ->namespace('Carpentree\Core\Http\Controllers')
    ->group(function () {

    Route::middleware(['api', 'auth:api', 'verified', 'scope:admin'])
        ->group(function() {

        /**
         * Users
         */
        Route::prefix('users')->group(function() {
            Route::get('/', 'Admin\UserController@list')
                ->name('users.list');

            Route::get('{id}', 'Admin\UserController@get')
                ->name('users.get');

            Route::post('/', 'Admin\UserController@create')
                ->name('users.create');

            Route::patch('/', 'Admin\UserController@update')
                ->name('users.update');

            Route::delete('{id}', 'Admin\UserController@delete')
                ->name('users.delete');
        });

        /**
         * Permissions
         */
        Route::get('permissions', 'Admin\PermissionController@list')
            ->name('permissions.list');

        /**
         * Roles
         */
        Route::get('roles', 'Admin\RoleController@list')
            ->name('roles.list');

        /**
         * Media
         */
        Route::prefix('media')->group(function() {
            Route::post('temp', 'UploadTemporary')
                ->name('media.upload-temp');
        });

    });

});
