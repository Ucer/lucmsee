<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->default(0);
            $table->string('table_name')->default('')->comment('表名');
            $table->string('type')->default('insert')->comment('类型');
            $table->ipAddress('ip')->default('')->comment('IP');
            $table->string('description')->default('')->comment('说明');
            $table->text('content')->comment('日志内容,json_encode(相关数据)');
            $table->softDeletes();
            $table->timestamps();

            $table->index('user_id');
            $table->index('table_name');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logs');
    }
}
