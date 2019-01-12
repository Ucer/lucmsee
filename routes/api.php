<?php

/**       ==========================          基本APi           ====================   */
Route::namespace('Api')->group(function () {

    Route::get('get_captcha', 'OtherController@getCaptcha')->name('other.get_captcha');

    Route::post('login', 'LoginController@login')->name('login');
});

