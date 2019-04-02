<?php

use Illuminate\Support\Facades\Route;

Route::prefix('oauth')->group(function() {

    Route::post('token', [
        'middleware' => ['throttle'],
        'uses' => '\Carpentree\Core\Http\Controllers\Auth\AccessTokenController@issueToken',
        'as' => 'passport.token',
    ]);

    Route::post('token/refresh', [
        'middleware' => ['web', 'auth'],
        'uses' => '\Laravel\Passport\Http\Controllers\TransientTokenController@refresh',
        'as' => 'passport.token.refresh',
    ]);
});

/**
 * Email verification
 */
Route::prefix('email')->namespace('Carpentree\Core\Http\Controllers\Auth')->group(function() {

    // Route::get('verify', 'Auth\VerificationController@show')->name('verification.notice');

    Route::middleware(['signed', 'throttle:6,1'])->group(function() {
        Route::get('verify/{id}', 'VerificationController@verify')->name('verification.verify');
    });

    Route::middleware(['auth:api', 'throttle:6,1'])->group(function() {
        Route::get('resend', 'VerificationController@resend')->name('verification.resend');
    });

});

/**
 * Passowrd reset and forgot
 */
Route::prefix('password')->namespace('Carpentree\Core\Http\Controllers\Auth')->group(function() {

    Route::middleware(['api'])->group(function() {
        // Route::get('reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.request');
        Route::post('email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    });

    // Route::get('reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('reset', 'ResetPasswordController@reset')->name('password.update');

});
