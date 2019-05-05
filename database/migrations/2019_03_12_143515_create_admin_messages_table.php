<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->default(0)->comment('哪个用户发的消息，对应 app 用户表');
            $table->integer('admin_id')->default(0)->comment('发给哪个管理员的消息,0为所有管理员');
            $table->string('title')->default('');
            $table->string('message_type')->default('system')->comment('消息类型');
            $table->string('content', 2000)->default('');
            $table->enum('is_read', ['T', 'F'])->default('F')->comment('是否已读');
            $table->timestamps();
            $table->softDeletes();

            $table->index('message_type');
            $table->index('is_read');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_messages');
    }
}
