<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResetDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::select('truncate table users');

        DB::select('SET FOREIGN_KEY_CHECKS=0');
        DB::select('truncate table roles');
        DB::select('truncate table model_has_roles');
        DB::select('truncate table role_has_permissions');
        DB::select('truncate table permissions');
        DB::select('truncate table model_has_permissions');
        DB::select('SET FOREIGN_KEY_CHECKS=1');


        DB::select('truncate table status_maps');
        DB::select('truncate table system_configs');
        DB::select('truncate table tables');
    }
}
