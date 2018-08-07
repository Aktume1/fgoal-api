<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateObjectivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('objectives', function (Blueprint $table) {
            $table->increments('id');
            $table->nullableMorphs('objectiveable');
            $table->boolean('is_private');
            $table->integer('group_id')->unsigned()->index();
            $table->integer('parent_id')->nullable()->unsigned()->index();
            $table->integer('weight')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('method');
            $table->string('description');
            $table->tinyInteger('status')->unsigned()->default(0);
            $table->tinyInteger('current')->unsigned()->default(0);
            $table->tinyInteger('improgress')->unsigned()->default(0);
            $table->tinyInteger('estimate')->unsigned()->default(0);
            $table->integer('unit_id')->unsigned()->index();
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
        Schema::dropIfExists('objectives');
    }
}
