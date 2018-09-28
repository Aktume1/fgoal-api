<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupTrackingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_tracking', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('group_id')->unsigned()->index();
            $table->integer('tracking_id')->unsigned()->index();
            $table->integer('actual')->nullable();
            $table->date('date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tracking_user');
    }
}
