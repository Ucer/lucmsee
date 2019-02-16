<?php

Route::get('/', 'IndexController@index');
Route::group(['prefix' => 'dashboard'], function () {
    Route::get('{path?}', 'IndexController@dashboard')->where('path', '[\/\w\.-]*');
});



