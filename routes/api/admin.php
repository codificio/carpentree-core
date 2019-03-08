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
         * Addresses
         */
        Route::prefix('addresses')->group(function() {
            Route::get('{id}', 'Admin\AddressController@get')
                ->name('addresses.get');

            Route::post('/', 'Admin\AddressController@create')
                ->name('addresses.create');

            Route::patch('/', 'Admin\AddressController@update')
                ->name('addresses.update');

            Route::delete('{id}', 'Admin\AddressController@delete')
                ->name('addresses.delete');
        });

        /**
         * Categories
         */
        Route::prefix('categories')->group(function() {
            Route::get('{type}', 'Admin\CategoryController@getByType')
                ->name('categories.get-by-type');

            Route::post('/', 'Admin\CategoryController@create')
                ->name('categories.create');

            Route::patch('/', 'Admin\CategoryController@update')
                ->name('categories.update');

            Route::delete('{id}', 'Admin\CategoryController@delete')
                ->name('categories.delete');
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
            Route::post('/', 'Admin\MediaController@upload')
                ->name('media.upload');

            Route::delete('{id}', 'Admin\MediaController@delete')
                ->name('media.delete');
        });

    });

});
