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
        $this->call(TableSeeder::class);
        $this->call(StatusMapsTableSeeder::class);
        $this->call(SystemConfigsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
    }
}
