<?php

use Illuminate\Database\Seeder;

class SystemConfigsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['flag' => 'max_bak_sql_file_size', 'title' => '数据库备份每个文件最大字节(单位为M)','config_group' => 'basic','value' => '20'],
            ['flag' => 'regex_email', 'title' => '正则邮箱验证','config_group' => 'basic','value' => '^[_.0-9a-z-]+@([0-9a-z][0-9a-z-]+.)+[a-z]{2,3}$'],
        ];
        \App\Models\Table::insert($data);

    }
}
