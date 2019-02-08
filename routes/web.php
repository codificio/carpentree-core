<?php

use Illuminate\Support\Facades\Route;

Route::prefix('email')->namespace('Carpentree\Core\Http\Controllers')->group(function() {

    // Route::get('verify', 'Auth\VerificationController@show')->name('verification.notice');
    Route::get('verify/{id}', 'Auth\VerificationController@verify')->name('verification.verify');
    Route::get('resend', 'Auth\VerificationController@resend')->name('verification.resend');

});
