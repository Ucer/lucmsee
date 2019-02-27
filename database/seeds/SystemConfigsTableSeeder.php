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
        ];
        \App\Models\SystemConfig::insert($data);

    }
}
