<?php

use Illuminate\Database\Seeder;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['table_name' => 'tables', 'table_name_cn' => '所有表管理'],
            ['table_name' => 'status_maps', 'table_name_cn' => '表状态管理'],
            ['table_name' => 'users', 'table_name_cn' => '用户表'],
            ['table_name' => 'logs', 'table_name_cn' => '日志表'],
            ['table_name' => 'permissions', 'table_name_cn' => '权限表'],
            ['table_name' => 'model_has_permissions', 'table_name_cn' => '权限关联表'],
            ['table_name' => 'roles', 'table_name_cn' => '角色表'],
            ['table_name' => 'model_has_roles', 'table_name_cn' => '角色关联表'],
            ['table_name' => 'attachments', 'table_name_cn' => '附件表'],
            ['table_name' => 'system_configs', 'table_name_cn' => '系统配置表'],
            ['table_name' => 'article_categories', 'table_name_cn' => '文章分类表'],
            ['table_name' => 'articles', 'table_name_cn' => '文章表'],
            ['table_name' => 'tags', 'table_name_cn' => '标签表'],
            ['table_name' => 'smses', 'table_name_cn' => '短信记录表'],
            ['table_name' => 'app_versions', 'table_name_cn' => 'app版本管理表'],
            ['table_name' => 'user_agreements', 'table_name_cn' => '用户协议'],
        ];
        \App\Models\Table::insert($data);
    }
}
