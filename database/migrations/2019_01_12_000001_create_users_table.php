<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('real_name')->default('');
            $table->string('nickname')->default('');
            $table->string('avatar')->default('')->comment('头像');
            $table->string('mobile')->default('');
            $table->string('email')->default('');
            $table->string('password')->default('');
            $table->enum('enable',['T','F'])->default('F')->comment('启用状态：F禁用，T启用');
            $table->enum('is_admin', ['T', 'F'])->default('F')->comment('是否可登录后台：F否，是');
            $table->string('description')->default('')->comment('一句话描述');
            $table->string('remark')->default('')->comment('备注');
            $table->string('remember_token')->default('');
            $table->timestamp('last_login_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index('mobile');
            $table->index('enable');
            $table->index('is_admin');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
