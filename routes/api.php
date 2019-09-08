<?php

/**       ==========================          基本APi part           ====================   */
Route::namespace('Api')->group(function () {

    Route::get('get_captcha', 'OtherController@getCaptcha')->name('other.get_captcha');

    Route::post('login', 'LoginController@login')->name('login');
    Route::post('logout', 'LoginController@logout')->name('logout');

    Route::post('common_switch_enable', 'CommonController@switchEnable')->name('common.switch_enable');
    Route::post('common_edit_talbe_column', 'CommonController@editTalbleColumn')->name('common.edit_table_column');
    Route::get('common_get_table_status/{table_name}/{column_name?}', 'CommonController@getTableStatus')->name('common.get_table_status');
    Route::get('common_get_system_config/{search_data}', 'CommonController@getSystemConfig')->name('common.get_system_config');
    Route::post('common_get_config_file_data', 'CommonController@getConfigData')->name('common.get_config_file_data');


    /**       ==========================          文件上传  part         ====================   */
    Route::post('uploads/common_upload/{file_type}/{category}', 'UploadController@commonUpload')->name('uploads.common_upload');

    /**       ==========================          Excel   part      ====================   */
    Route::post('excels/export_excel_log', 'ExcelsController@exportExcelLog')->name('excels.export_excel_log');
    Route::post('excels/import_excel_tag', 'ExcelsController@importExcelTag')->name('excels.import_excel_tag');

});


/**       ==========================          其它域名来访Api    part     ====================   */
Route::namespace('Api')->prefix('accept')->group(function () {
    Route::post('common/get_config_data', 'AcceptCommonAccessController@getConfigData')->name('accept.common.get_config_data');
    Route::post('common/upload_image_use_base64', 'AcceptCommonAccessController@uploadImageUseBase64')->name('accept.common.upload_image_use_base64');
});

/**       ==========================          测试域名    part     ====================   */
Route::namespace('Api')->prefix('test')->middleware('only_dev_model')->group(function () {
    Route::post('testJob', 'TestController@testJob')->name('test.testJob');
    Route::post('testMessageSend', 'TestController@testMessageSend')->name('test.testMessageSend');
});

/**       ==========================          后台自带 Api   part          ====================   */
Route::namespace('Admin')->group(function () {

    Route::get('admin/users/current_user', 'UsersController@currentUser')->name('users.current_user');
    Route::get('admin/users/search_user_pass_mobile/{mobile}', 'UsersController@searchUserPassMobile')->name('users.search_user_pass_mobile');
    Route::get('admin/users/search_admin_user_pass_email/{email}', 'UsersController@searchAdminUserPassEmail')->name('users.search_admin_user_pass_email');
    Route::get('admin/statistics', 'StatisticsController@base')->name('statistics.base');
    Route::post('admin/users/reset_password', 'UsersController@resetPassword')->name('users.reset_password');


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


    Route::get('admin/tables/get_all_tables/{table_name?}', 'TablesController@getAllTables')->name('tables.get_all_tables');

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

    Route::get('admin/articles', 'ArticlesController@list')->name('articles.list');
    Route::post('admin/articles', 'ArticlesController@store')->name('articles.store');
    Route::patch('admin/articles/{article}', 'ArticlesController@update')->name('articles.update');
    Route::get('admin/articles/{article}', 'ArticlesController@show')->name('articles.show');
    Route::delete('admin/articles/{article}', 'ArticlesController@destroy')->name('articles.destroy');

    Route::get('admin/app_messages', 'AppMessagesController@list')->name('app_messages.list');
    Route::post('admin/app_messages/send_message_to_app_user', 'AppMessagesController@sendMessageToAppUser')->name('app_messages.send_message_to_app_user');
    Route::delete('admin/app_messages/{app_message}', 'AppMessagesController@destroy')->name('app_messages.destroy');
    Route::delete('admin/app_messages/{ids}/batch', 'AppMessagesController@destroyBatch')->name('app_messages.destroy_batch');

    Route::get('admin/admin_messages', 'AdminMessagesController@list')->name('admin_messages.list');
    Route::post('admin/admin_messages/send_message_to_admin', 'AdminMessagesController@sendMessageToAdmin')->name('admin_messages.send_message_to_admin');
    Route::delete('admin/admin_messages/{admin_message}', 'AdminMessagesController@destroy')->name('admin_messages.destroy');
    Route::delete('admin/admin_messages/{ids}/batch', 'AdminMessagesController@destroyBatch')->name('admin_messages.destroy_batch');
    Route::patch('admin/admin_messages/read_all', 'AdminMessagesController@readAll')->name('admin_messages.read_all');
    Route::patch('admin/admin_messages/read/{admin_message}', 'AdminMessagesController@readOne')->name('admin_messages.read_one');


    Route::get('admin/carousels', 'CarouselsController@list')->name('carousels.list');
    Route::post('admin/carousels', 'CarouselsController@store')->name('carousels.store');
    Route::patch('admin/carousels/{carousel}', 'CarouselsController@update')->name('carousels.update');
    Route::get('admin/carousels/{carousel}', 'CarouselsController@show')->name('carousels.show');
    Route::delete('admin/carousels/{carousel}', 'CarouselsController@destroy')->name('carousels.destroy');


    Route::get('admin/logs', 'LogsController@list')->name('logs.list');
    Route::get('admin/logs/{log}', 'LogsController@show')->name('logs.show');
    Route::delete('admin/logs/{log}', 'LogsController@destroy')->name('logs.destroy');


    Route::get('admin/app_versions', 'AppVersionsController@list')->name('app_versions.list');
    Route::post('admin/app_versions', 'AppVersionsController@store')->name('app_versions.store');
    Route::patch('admin/app_versions/{app_version}', 'AppVersionsController@update')->name('app_versions.update');
    Route::get('admin/app_versions/{app_version}', 'AppVersionsController@show')->name('app_versions.show');
    Route::delete('admin/app_versions/{app_version}', 'AppVersionsController@destroy')->name('app_versions.destroy');


    Route::patch('admin/user_agreements/enable_or_disable/{user_agreement}', 'UserAgreementsController@enableOrDisable')->name('user_agreements.enable_or_disable');
    Route::get('admin/user_agreements', 'UserAgreementsController@list')->name('user_agreements.list');
    Route::post('admin/user_agreements', 'UserAgreementsController@store')->name('user_agreements.store');
    Route::patch('admin/user_agreements/{user_agreement}', 'UserAgreementsController@update')->name('user_agreements.update');
    Route::get('admin/user_agreements/{user_agreement}', 'UserAgreementsController@show')->name('user_agreements.show');
    Route::delete('admin/user_agreements/{user_agreement}', 'UserAgreementsController@destroy')->name('user_agreements.destroy');
});


/**       ==========================          后台自定义 Api   part          ====================   */
Route::namespace('Admin')->group(function () {
    /**
     * 实际项目中，你自己定义的路由应写在此处
     **/
});
