<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('logs');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $table->increments('id');
        $table->integer('user_id')->unsigned();
        $table->integer('group_id')->unsigned();
        $table->string('type')->nullable();
        $table->integer('logable_id')->nullable();
        $table->string('action')->nullable();
        $table->string('property')->nullable();
        $table->string('old_value')->nullable();
        $table->string('new_value')->nullable();
        $table->timestamps();
    }
}
