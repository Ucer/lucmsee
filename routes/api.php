<?php

    /**       ==========================          基本APi           ====================   */
Route::namespace('Api')->group(function () {

    Route::get('get_captcha', 'OtherController@getCaptcha')->name('other.get_captcha');

    Route::post('login', 'LoginController@login')->name('login');
});


    /**       ==========================          后台APi           ====================   */
Route::namespace('Admin')->group(function () {

    /**       ==========================          自带 Api           ====================   */
    Route::get('admin/users/current_user', 'UserController@currentUser')->name('users.current_user');
    Route::get('admin/statistics', 'StatisticsController@base')->name('statistics.base');


    Route::get('admin/permissions', 'PermissionsController@list')->name('permissions.list');
    Route::post('admin/permissions', 'PermissionsController@store')->name('permissions.store');
    Route::patch('admin/permissions/{permission}', 'PermissionsController@update')->name('permissions.update');
    Route::get('admin/all_permissions', 'PermissionsController@allPermissions')->name('permissions.all');
    Route::get('admin/permissions/{permission}', 'PermissionsController@show')->name('permissions.show');
    Route::delete('admin/permissions/{permission}', 'PermissionsController@destroy')->name('permissions.destroy');

});
