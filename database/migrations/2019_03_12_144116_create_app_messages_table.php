<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('admin_id')->default(0)->comment('哪个管理员发的消息');
            $table->integer('user_id')->default(0)->comment('发给哪个用户的消息,0为所有管理员');
            $table->string('title')->default('');
            $table->string('content')->default('');
            $table->string('url')->default('')->comment('跳转url');
            $table->string('message_type')->default('system')->comment('消息类型');
            $table->enum('is_read', ['T', 'F'])->default('F')->comment('是否已读');
            $table->enum('is_alert_at_home', ['F', 'T'])->default('F')->comment('是否在首页弹出提示框，已读后就不再弹出');
            $table->timestamps();
            $table->softDeletes();

            $table->index('message_type');
            $table->index('is_read');
            $table->index('user_id');
            $table->index('is_alert_at_home');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('app_messages');
    }
}
