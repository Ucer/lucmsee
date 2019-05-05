<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attachments', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->integer('user_id')->default(0)->comment('上传用户 id');
            $table->ipAddress('ip')->default('')->comment('附件上传者 ip');
            $table->string('original_name')->default('')->comment('原始名称');
            $table->string('mime_type')->default('')->comment('mime 类型');
            $table->enum('file_type', ['pic', 'file', 'video'])->default('pic')->comment('类型');
            $table->string('size')->default('0')->comment('大小/kb');
            $table->string('category')->default('tmp')->comment('归类');
            $table->string('domain')->default('')->comment('域名地址,https://jiayouhaoshi.com');
            $table->string('storage_path')->default('')->comment('附件相对 storage 目录,app/public/images/avatars');
            $table->string('link_path')->default('')->comment('附件相对网站根目录,访问路径：storage/images/avatars');
            $table->string('storage_name')->default('')->comment('存储名称');
            $table->string('remark')->default('')->comment('附件备注');
            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
            $table->index('file_type');
            $table->index('category');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attachments');
    }
}
