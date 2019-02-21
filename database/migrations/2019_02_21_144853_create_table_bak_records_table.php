<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableBakRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_bak_records', function (Blueprint $table) {
            $table->increments('id');
            $table->text('bak_tables_name')->comment('备份的表名称，多个以逗号隔开;users,logs');
            $table->integer('user_id')->default(0);
            $table->tinyInteger('file_num')->default(1)->comment('产生文件数量');
            $table->text('files')->comment('json_encode([/data/wwwroot/lucmsee=2019-01-01=11:53:54=ABC=1.sql,/data/wwwroot/lucmsee=2019-01-01=11:53:54=ABC=2.sql])');
            $table->integer('file_size')->default(0)->comment('单位为字节');
            $table->timestamps();
            $table->softDeletes();

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
        Schema::dropIfExists('table_bak_records');
    }
}
