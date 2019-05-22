<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateRftBalanceLogsTable extends Migration
{
    public function up()
    {
        Schema::create('action_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable()->comment('操作用户ID');
            $table->morphs('model');
            $table->string('type')->comment('操作类型 create, update, delete');
            $table->text('old_data')->comment('修改前数据')->nullable();
            $table->text('new_data')->comment('修改后数据')->nullable();
            $table->ipAddress('ip')->nullable();
            $table->string('url')->nullable();
            $table->string('agent')->nullable();
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('action_logs');
    }
}