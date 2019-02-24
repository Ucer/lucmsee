<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSystemConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_configs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('flag')->default('')->comment('配置英文标识');
            $table->string('title')->default('')->comment('配置标题');
            $table->string('config_group')->default('basic')->comment('配置分组');
            $table->string('value',600)->default('')->comment('配置值');
            $table->string('description')->default('')->comment('配置描述');
            $table->enum('enable', ['T', 'F'])->default('T');
            $table->timestamps();

            $table->index('flag');
            $table->index('enable');
            $table->index('config_group');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_configs');
    }
}
