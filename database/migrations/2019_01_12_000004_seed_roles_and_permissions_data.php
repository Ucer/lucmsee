<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SeedRolesAndPermissionsData extends Migration
{
    public function up()
    {
        // 清除缓存
        app()['cache']->forget('spatie.permission.cache');

        // 先创建权限
        Permission::create(['name' => 'manage_contents', 'description' => '管理内容']);
        Permission::create(['name' => 'manage_users', 'description' => '管理用户']);
        Permission::create(['name' => 'edit_settings', 'description' => '数据添加修改']);

        // 创建站长角色，并赋予权限
        $founder = Role::create(['name' => 'Founder', 'description' => '站长']);
        $founder->givePermissionTo('manage_contents');
        $founder->givePermissionTo('manage_users');
        $founder->givePermissionTo('edit_settings');

        // 创建管理员角色，并赋予权限
        $maintainer = Role::create(['name' => 'Maintainer', 'description' => '管理员']);
        $maintainer->givePermissionTo('manage_contents');

        // 创建网站编辑角色，并赋予权限
        $maintainer = Role::create(['name' => 'WebsiteEditor', 'description' => '网站编辑']);
        $maintainer->givePermissionTo('manage_contents');
    }

    public function down()
    {
        // 清除缓存
        app()['cache']->forget('spatie.permission.cache');

        // 清空所有数据表数据
        $tableNames = config('permission.table_names');

        Model::unguard();
        DB::table($tableNames['role_has_permissions'])->delete();
        DB::table($tableNames['model_has_roles'])->delete();
        DB::table($tableNames['model_has_permissions'])->delete();
        DB::table($tableNames['roles'])->delete();
        DB::table($tableNames['permissions'])->delete();
        Model::reguard();
    }
}
