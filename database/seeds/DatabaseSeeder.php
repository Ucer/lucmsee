<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ResetDataSeeder::class); // 清除已有表所有数据
        $this->call(TableSeeder::class);
        $this->call(StatusMapsTableSeeder::class);
        $this->call(SystemConfigsTableSeeder::class);
        $this->call(RolesAndPermissionsTableSeeder::class); // 要放到 UsersTableSeeder 前面
        $this->call(UsersTableSeeder::class);
    }
}
