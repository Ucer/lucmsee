<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class AddWechatInfoToUsersTable
 */
class AddWechatInfoToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('weixin_openid', 100)->default('');
            $table->string('mini_openid', 100)->default('')->comment('小程序 openid');
            $table->string('weixin_unionid', 100)->default('');
            $table->string('weixin_session_key', 100)->default('');
            $table->string('country', 30)->default('');
            $table->string('province', 30)->default('');
            $table->string('city', 30)->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('weixin_openid');
            $table->dropColumn('mini_openid');
            $table->dropColumn('weixin_unionid');
            $table->dropColumn('weixin_session_key');
            $table->dropColumn('country');
            $table->dropColumn('province');
            $table->dropColumn('city');
        });
    }
}
