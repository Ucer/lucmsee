<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppVersionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_versions', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('name', ['lucmsee'])->default('lucmsee')->comment('app名称');
            $table->enum('mobile_os', ['android', 'ios', 'all'])->default('all');
            $table->string('version_sn')->default('1.0.0');
            $table->string('description', '1000')->default('');
            $table->string('package_url')->default('')->comment('包地址');
            $table->enum('is_force_update', ['T', 'F'])->default('F')->comment('是否强制更新');

            $table->index('version_sn');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('app_versions');
    }
}
