<?php

use Illuminate\Support\Facades\Route;

/**
 * Email verification
 */
Route::prefix('email')->namespace('Carpentree\Core\Http\Controllers\Auth')->group(function() {

    // Route::get('verify', 'Auth\VerificationController@show')->name('verification.notice');

    Route::middleware(['auth:api', 'signed', 'throttle:6,1'])->group(function() {
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
        // $this->get('reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.request');
        $this->post('email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    });

    // $this->get('reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
    $this->post('reset', 'ResetPasswordController@reset')->name('password.update');

});
