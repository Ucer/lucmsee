<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // 生成数据集合
        $users = factory(\App\Models\User::class)
            ->times(3)
            ->make();

        // 让隐藏字段可见，并将数据集合转换为数组
        $user_array = $users->makeVisible(['password', 'remember_token'])->toArray();

        // 插入到数据库中
        \App\Models\User::insert($user_array);

        // 单独处理第一个用户的数据
        $user = \App\Models\User::find(1);
        $user->nickname = 'ucer';
        $user->real_name = '张老大';
        $user->email = 'dev@lucms.com';
        $user->enable = 'T';
        $user->is_admin = 'T';
        $user->save();

        $user->assignRole('Founder', "Developer");

        $user = \App\Models\User::find(2);
        $user->nickname = '小管家';
        $user->real_name = '管理员';
        $user->email = 'xgj@lucms.com';
        $user->enable = 'T';
        $user->is_admin = 'T';
        $user->save();

        $user->assignRole('Maintainer');

        $user = \App\Models\User::find(3);
        $user->nickname = '小编辑';
        $user->real_name = '网站编辑';
        $user->email = 'xbj@lucms.com';
        $user->enable = 'T';
        $user->is_admin = 'T';
        $user->save();

        $user->assignRole('WebsiteEditor');
    }
}
