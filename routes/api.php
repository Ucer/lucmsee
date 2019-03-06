<?php

/**       ==========================          基本APi           ====================   */
Route::namespace('Api')->group(function () {

    Route::get('get_captcha', 'OtherController@getCaptcha')->name('other.get_captcha');

    Route::post('login', 'LoginController@login')->name('login');

    Route::post('common_switch_enable', 'CommonController@switchEnable');
    Route::post('common_edit_talbe_column', 'CommonController@editTalbleColumn')->name('common.edit_table_column');
    Route::get('common_get_table_status/{table_name}/{column_name?}', 'CommonController@getTableStatus');
    Route::get('common_get_system_config/{search_data}', 'CommonController@getSystemConfig')->name('common.get_system_config');


    /**       ==========================          文件上传           ====================   */
    Route::post('uploads/common_upload/{file_type}/{image_category}', 'UploadController@commonUpload')->name('uploads.common_upload');

});


/**       ==========================          后台APi           ====================   */
Route::namespace('Admin')->group(function () {

    /**       ==========================          自带 Api           ====================   */
    Route::get('admin/users/current_user', 'UsersController@currentUser')->name('users.current_user');
    Route::get('admin/statistics', 'StatisticsController@base')->name('statistics.base');


    Route::get('admin/permissions', 'PermissionsController@list')->name('permissions.list');
    Route::post('admin/permissions', 'PermissionsController@store')->name('permissions.store');
    Route::patch('admin/permissions/{permission}', 'PermissionsController@update')->name('permissions.update');
    Route::get('admin/all_permissions', 'PermissionsController@allPermissions')->name('permissions.all');
    Route::get('admin/permissions/{permission}', 'PermissionsController@show')->name('permissions.show');
    Route::delete('admin/permissions/{permission}', 'PermissionsController@destroy')->name('permissions.destroy');

    Route::get('admin/roles', 'RolesController@roleList')->name('roles.list');
    Route::get('admin/roles/all_roles', 'RolesController@allRoles')->name('roles.all');
    Route::post('admin/roles', 'RolesController@store')->name('roles.store');
    Route::patch('admin/roles/{role}', 'RolesController@update')->name('roles.update');
    Route::get('admin/roles/{role}', 'RolesController@show')->name('roles.show');
    Route::get('admin/roles/{role}/permissions', 'RolesController@getRolePermissions')->name('roles.get_role_permissions');
    Route::post('admin/give/{role}/permissions', 'RolesController@giveRolePermissions')->name('roles.give_role_permissions');
    Route::delete('admin/roles/{role}', 'RolesController@destroy')->name('roles.destroy');

    Route::get('admin/users', 'UsersController@list')->name('users.list');
    Route::post('admin/users', 'UsersController@store')->name('users.store');
    Route::get('admin/users/{user}', 'UsersController@show')->name('users.show');
    Route::patch('admin/users/{user}', 'UsersController@update')->name('users.update');
    Route::delete('admin/users/{user}', 'UsersController@destroy')->name('users.destroy');
    Route::get('admin/users/{user}/roles', 'UsersController@getUserRoles')->name('users.get_user_roles');
    Route::post('admin/users/give/{user}/roles', 'UsersController@giveUserRoles')->name('users.give_user_roles');


    Route::get('admin/tables', 'TablesController@list')->name('tables.list');
    Route::post('admin/tables', 'TablesController@store')->name('tables.store');
    Route::get('admin/tables/{table}', 'TablesController@show')->name('tables.show');
    Route::patch('admin/tables/{table}', 'TablesController@update')->name('tables.update');
    Route::delete('admin/tables/{table}', 'TablesController@destroy')->name('tables.destroy');

    Route::get('admin/status_maps', 'StatusMapsController@list')->name('status_maps.list');
    Route::post('admin/status_maps', 'StatusMapsController@store')->name('status_maps.store');
    Route::get('admin/status_maps/{status_map}', 'StatusMapsController@show')->name('status_maps.show');
    Route::patch('admin/status_maps/{status_map}', 'StatusMapsController@update')->name('status_maps.update');
    Route::delete('admin/status_maps/{status_map}', 'StatusMapsController@destroy')->name('status_maps.destroy');


    Route::post('admin/databases/bak_table', 'DatabasesController@bakTable')->name('databases.bak_table');
    Route::post('admin/databases/optimize_table', 'DatabasesController@optimizeTable')->name('databases.optimize_table');
    Route::post('admin/databases/repair_table', 'DatabasesController@repairTable')->name('databases.repair_table');
    Route::get('admin/databases/table_bak_records', 'DatabasesController@tableBakRecords')->name('databases.table_bak_records');
    Route::get('admin/databases/{table_bak_record_id}/table_bak_sql_file_download', 'DatabasesController@tableBakSqlFileDownload')->name('databases.table_bak_sql_file_download');
    Route::get('admin/databases', 'DatabasesController@list')->name('databases.list');
    Route::delete('admin/databases/destroy_many_table_bak_record', 'DatabasesController@destroyManyTableBakRecord')->name('databases.destroy_many_table_bak_record');


    Route::get('admin/attachments', 'AttachmentsController@list')->name('attachments.list');
    Route::delete('admin/attachments/{attachment}', 'AttachmentsController@destroy')->name('attachments.destroy');


    Route::get('admin/system_configs/get_system_config_group', 'SystemConfigsController@getSystemConfigGroup')->name('system_configs.get_system_config_group');
    Route::get('admin/system_configs', 'SystemConfigsController@list')->name('system_configs.list');
    Route::post('admin/system_configs', 'SystemConfigsController@store')->name('system_configs.store');
    Route::get('admin/system_configs/{system_config}', 'SystemConfigsController@show')->name('system_configs.show');
    Route::patch('admin/system_configs/{system_config}', 'SystemConfigsController@update')->name('system_configs.update');
    Route::delete('admin/system_configs/{system_config}', 'SystemConfigsController@destroy')->name('system_configs.destroy');


    Route::get('admin/article_categories', 'ArticleCategoriesController@list')->name('article_categories.list');
    Route::post('admin/article_categories', 'ArticleCategoriesController@store')->name('article_categories.store');
    Route::patch('admin/article_categories/{article_category}', 'ArticleCategoriesController@update')->name('article_categories.update');
    Route::get('admin/article_categories/all_article_categories', 'ArticleCategoriesController@getAllCategories')->name('article_categories.all_article_categories');
    Route::get('admin/article_categories/{article_category}', 'ArticleCategoriesController@show')->name('article_categories.show');
    Route::delete('admin/article_categories/{article_category}', 'ArticleCategoriesController@destroy')->name('article_categories.destroy');


    Route::get('admin/tags', 'TagsController@list')->name('tags.list');
    Route::post('admin/tags', 'TagsController@store')->name('tags.store');
    Route::patch('admin/tags/{tag}', 'TagsController@update')->name('tags.update');
    Route::get('admin/tags/{tag}', 'TagsController@show')->name('tags.show');
    Route::delete('admin/tags/{tag}', 'TagsController@destroy')->name('tags.destroy');
});
