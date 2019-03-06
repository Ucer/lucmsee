<?php

use Illuminate\Database\Seeder;

class StatusMapsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            // users_table
            ['table_name' => 'users', 'column' => 'enable', 'status_code' => 'T', 'status_description' => '启用', 'remark' => '是否启用'],
            ['table_name' => 'users', 'column' => 'enable', 'status_code' => 'F', 'status_description' => '禁用', 'remark' => '是否启用'],

            ['table_name' => 'users', 'column' => 'is_admin', 'status_code' => 'T', 'status_description' => '是', 'remark' => '是否为后台管理员：可登录'],
            ['table_name' => 'users', 'column' => 'is_admin', 'status_code' => 'F', 'status_description' => '否', 'remark' => '是否为后台管理员：不可登录'],
            // logs_table
            ['table_name' => 'logs', 'column' => 'type', 'status_code' => 'insert', 'status_description' => '新增', 'remark' => '新增数据'],
            ['table_name' => 'logs', 'column' => 'type', 'status_code' => 'update', 'status_description' => '修改', 'remark' => '修改数据'],
            ['table_name' => 'logs', 'column' => 'type', 'status_code' => 'destroy', 'status_description' => '删除', 'remark' => '删除数据'],
            ['table_name' => 'logs', 'column' => 'type', 'status_code' => 'error', 'status_description' => '错误日志', 'remark' => '错误日志记录'],
            // attachments_table
            ['table_name' => 'attachments', 'column' => 'file_type', 'status_code' => 'file', 'status_description' => '文件', 'remark' => ''],
            ['table_name' => 'attachments', 'column' => 'file_type', 'status_code' => 'pic', 'status_description' => '图片', 'remark' => ''],
            ['table_name' => 'attachments', 'column' => 'file_type', 'status_code' => 'video', 'status_description' => '视频', 'remark' => ''],

            ['table_name' => 'attachments', 'column' => 'category', 'status_code' => 'lucmsee', 'status_description' => '系统附件', 'remark' => ''],
            // logs_table
            ['table_name' => 'system_configs', 'column' => 'enable', 'status_code' => 'T', 'status_description' => '启用', 'remark' => ''],
            ['table_name' => 'system_configs', 'column' => 'enable', 'status_code' => 'F', 'status_description' => '禁用', 'remark' => ''],
            // article_categories_table
            ['table_name' => 'article_categories', 'column' => 'enable', 'status_code' => 'T', 'status_description' => '启用', 'remark' => ''],
            ['table_name' => 'article_categories', 'column' => 'enable', 'status_code' => 'F', 'status_description' => '禁用', 'remark' => ''],
            // articles_table
            ['table_name' => 'articles', 'column' => 'enable', 'status_code' => 'T', 'status_description' => '启用', 'remark' => ''],
            ['table_name' => 'articles', 'column' => 'enable', 'status_code' => 'F', 'status_description' => '禁用', 'remark' => ''],

            ['table_name' => 'articles', 'column' => 'recommend', 'status_code' => 'T', 'status_description' => '启用', 'remark' => ''],
            ['table_name' => 'articles', 'column' => 'recommend', 'status_code' => 'F', 'status_description' => '禁用', 'remark' => ''],

            ['table_name' => 'articles', 'column' => 'top', 'status_code' => 'T', 'status_description' => '启用', 'remark' => ''],
            ['table_name' => 'articles', 'column' => 'top', 'status_code' => 'F', 'status_description' => '禁用', 'remark' => ''],

            ['table_name' => 'articles', 'column' => 'access_type', 'status_code' => 'pub', 'status_description' => '公开', 'remark' => ''],
            ['table_name' => 'articles', 'column' => 'access_type', 'status_code' => 'pri', 'status_description' => '私密', 'remark' => ''],
            ['table_name' => 'articles', 'column' => 'access_type', 'status_code' => 'pwd', 'status_description' => '密码访问', 'remark' => ''],
        ];
        \App\Models\StatusMap::insert($data);
    }
}
