<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('title')->default('')->comment('文章标题');
            $table->string('slug')->default('')->comment('slug');
            $table->string('keywords')->default('')->comment('关键词,以英文逗号隔开');
            $table->string('description')->default('')->comment('描述');
            $table->string('cover_image')->default('')->comment('封面图片');
            $table->text('content')->comment('内容');
            $table->integer('user_id')->default(0)->comment('作者 id');
            $table->integer('article_category_id')->default(0)->comment('分类 id');
            $table->unsignedInteger('view_count')->default(0)->comment('查看数量');
            $table->unsignedInteger('vote_count')->default(0)->comment('点赞数量');
            $table->unsignedInteger('comment_count')->default(0)->comment('评论数量');
            $table->unsignedInteger('collection_count')->default(0)->comment('收藏数量');
            $table->enum('enable', ['T', 'F'])->default('F')->comment('启用状态：F禁用，T启用');
            $table->enum('recommend', ['T', 'F'])->default('F')->comment('是否推荐到首页');
            $table->enum('top', ['T', 'F'])->default('F')->comment('是否置顶');
            $table->integer('weight')->default(20)->comment('权重');
            $table->enum('access_type',['pub','pri','pwd'])->default('pub')->comment('访问权限类型：公开、私密、密码访问');
            $table->string('access_value')->default('')->comment('访问权限值：pub->不公开的用户ids 逗号隔开,为空则所有人都能访问,pri->公开的用户ids 逗号隔开，为空则只有作者能访问,pwd->访问密码，aes-ecb-128加密方式加密');
            $table->timestamps();

            $table->index('weight');
            $table->index('article_category_id');
            $table->index('user_id');
            $table->index('access_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
}
