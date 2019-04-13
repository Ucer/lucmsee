<?php

Route::get('/', 'IndexController@index')->name('web.home');
Route::get('/docs', 'IndexController@docs')->name('web.docs');

Route::group(['prefix' => 'dashboard'], function () {
    Route::get('{path?}', 'IndexController@dashboard')->where('path', '[\/\w\.-]*');
});
Route::get('/photo_editor', 'ToolsController@photoEditor');



