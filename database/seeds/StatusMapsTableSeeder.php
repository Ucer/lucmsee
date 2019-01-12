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
            ['table_name' => 'users', 'column' => 'enable', 'status_code' => 'T', 'status_description' => '启用', 'remark' => '是否启用'],
            ['table_name' => 'users', 'column' => 'enable', 'status_code' => 'F', 'status_description' => '禁用', 'remark' => '是否启用'],
            ['table_name' => 'is_admin', 'column' => 'is_admin', 'status_code' => 'T', 'status_description' => '是', 'remark' => '是否为后台管理员：可登录'],
            ['table_name' => 'is_admin', 'column' => 'is_admin', 'status_code' => 'F', 'status_description' => '否', 'remark' => '是否为后台管理员：不可登录']
        ];
        \App\Models\StatusMap::insert($data);
    }
}
