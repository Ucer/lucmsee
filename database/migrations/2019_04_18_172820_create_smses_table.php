<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('mobile');
            $table->string('type')->default('test')->commit('短信类型');
            $table->string('code');
            $table->ipAddress('ip');
            $table->timestamps();

            $table->index('mobile');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('smses');
    }
}
