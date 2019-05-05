<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserAgreementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_agreements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('agree_type')->comment("协议类型");
            $table->string("name")->default("")->comment("协议名称");
            $table->string("description")->default("")->comment("协议描述");
            $table->longText("content")->comment("协议内容");
            $table->integer("user_id")->default(0)->comment("操作人");
            $table->enum("enable", ['T', 'F'])->default('F')->comment("是否启用");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_agreements');
    }
}
