<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('code')->nullable();
            $table->date('birthday')->nullable();
            $table->tinyInteger('gender')->nullable();
            $table->string('phone')->nullable();
            $table->string('mission')->nullable();
            $table->integer('group_id')->nullable();
            // parent_id
            $table->integer('manager_id')->nullable();
            $table->string('localtion')->nullable();
            $table->string('avatar')->nullable();
            $table->string('token_verification')->nullable();
            $table->integer('status')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletesTz();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
